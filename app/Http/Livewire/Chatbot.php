<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Mindwave\Mindwave\Facades\Mindwave;
use Mindwave\Mindwave\Memory\ConversationBufferMemory;

class Chatbot extends Component
{
    public Collection $messages;

    public string $draft = '';

    public string $mode = 'guest';

    public function mount()
    {
        $this->messages = Session::get('chat', collect());
    }

    public function sendMessage()
    {
        $msg = trim($this->draft);

        if ($msg == '/clear') {
            $this->messages = collect();
            Session::put('chat', $this->messages);
            $this->reset('draft');

            return;
        }

        $history = ConversationBufferMemory::fromMessages($this->messages->toArray());

        if ($msg == '/debug') {
            $this->messages->push([
                'role' => 'debug',
                'content' => $history->conversationAsString('Human', 'Mindwave'),
            ]);

            return;
        }

        $agent = Mindwave::agent($history);

        $this->messages->push([
            'role' => 'user',
            'content' => $this->draft,
        ]);

        $response = $agent->ask($this->draft);

        $this->messages->push([
            'role' => 'assistant',
            'content' => $response,
        ]);

        $this->reset('draft');
        Session::put('chat', $this->messages);

        $this->emit('message');
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}
