<?php

namespace App\Http\Livewire;

use App\Models\Document;
use Livewire\Component;

class ShowDocument extends Component
{
    public Document $document;

    public function render()
    {
        return view('livewire.show-document');
    }
}
