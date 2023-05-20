<?php

namespace App\Http\Livewire;

use App\Models\Document;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Documents extends Component
{
    use WithFileUploads;
    use WithPagination;

    /** @var TemporaryUploadedFile[] */
    public $uploadedDocuments = [];
    public $search;

    protected $queryString = [
        "search" => ["except" => ""],
    ];

    public function save()
    {
        $this->validate([
            'uploadedDocuments.*' => 'max:1024',
        ]);

        foreach ($this->uploadedDocuments as $document) {
            $document->store('documents');
        }
    }

    public function getDocumentsQuery(): Builder
    {
        return Document::query()
            ->when($this->search, fn(Builder $q, $value) => $q->where("filename", "like", "%$value%"))
            ->latest();
    }

    public function render()
    {
        return view('livewire.documents', [
            'documents' => $this->getDocumentsQuery()->simplePaginate(),
        ]);
    }
}
