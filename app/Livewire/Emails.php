<?php

namespace App\Livewire;

use App\Models\Email;
use Livewire\Component;

class Emails extends Component
{
    public $search = '';

    public function render()
    {

        return view('livewire.emails', [
            'emails' => Email::query()
                ->when($this->search, fn ($query) => $query->where('subject', 'like', "%{$this->search}%"))
                ->latest()
                ->get(),
        ]);
    }
}
