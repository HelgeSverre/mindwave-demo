<?php

namespace App\Http\Livewire;

use App\Jobs\ConsumeDocument;
use App\Models\Document;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

/**
 * @property-read Paginator|Document[] $documents
 */
class Documents extends Component
{
    use WithFileUploads;
    use WireToast;
    use WithPagination;

    /** @var TemporaryUploadedFile */
    public $uploadedDocument;

    public $uploads = [];

    public $search;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatedUploadedDocument()
    {
        $this->validate([
            'uploadedDocument.*' => 'max:1024',
        ]);

        ConsumeDocument::dispatch(Document::create([
            'path' => $this->uploadedDocument->store('documents'),
            'filename' => $this->uploadedDocument->getClientOriginalName(),
        ]));
    }

    public function saveUploads()
    {
        foreach ($this->uploads as $upload) {
            $document = Document::createFromS3($upload);
            if (! $document) {
                toast()->warning("Failed to upload ({$upload['name']})")->push();

                continue;
            }
            ConsumeDocument::dispatch($document);
        }

        $this->uploads = [];
    }

    public function delete(Document $document)
    {
        $document->delete();
    }

    public function deleteAll()
    {
        Document::all()->each(fn (Document $document) => $document->delete());
    }

    public function getDocumentsProperty()
    {
        return Document::query()
            ->when($this->search, fn (Builder $q, $value) => $q->where('filename', 'like', "%$value%"))
            ->latest()
            ->simplePaginate();
    }

    public function render()
    {
        return view('livewire.documents');
    }
}
