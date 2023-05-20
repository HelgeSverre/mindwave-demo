<?php

namespace App\Http\Livewire;

use App\Models\Document;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithFileUploads;

class Documents extends Component
{
    use WithFileUploads;

    public $uploadedDocuments = [];

    public function save()
    {
        $this->validate([
            'uploadedDocuments.*' => 'max:1024',
        ]);

        foreach ($this->uploadedDocuments as $documents) {
            $documents->store('documents');
        }
    }

    public function getDocumentsQuery(): Builder
    {
        return Document::query();
    }

    public function render()
    {
        return view('livewire.documents', [
            'documents' => $this->getDocumentsQuery()->paginate(),
        ]);
    }
}
