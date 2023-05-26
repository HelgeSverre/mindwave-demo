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

/**
 * @property-read Paginator|Document[] $documents
 */
class Documents extends Component
{
    use WithFileUploads;
    use WithPagination;

    /** @var TemporaryUploadedFile */
    public $uploadedDocument;

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
