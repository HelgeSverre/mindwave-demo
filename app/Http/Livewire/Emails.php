<?php

namespace App\Http\Livewire;

use App\Models\Email;
use Livewire\Component;

class Emails extends Component
{
    public function render()
    {

        return view('livewire.emails', [
            'emails' => Email::all(),
        ]);
    }
}
