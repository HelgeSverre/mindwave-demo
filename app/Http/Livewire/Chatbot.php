<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Mindwave\Mindwave\Facades\Mindwave;
use Mindwave\Mindwave\Memory\ConversationBufferMemory;
use Throwable;
use Usernotnull\Toast\Concerns\WireToast;

class Chatbot extends Component
{
    use WireToast;

    public Collection $messages;

    public string $draft = '';

    public bool $debug = false;

    public function mount()
    {
        $this->messages = Session::get('chat', collect());
        $this->debug = Session::get('debug') ?? false;
    }

    protected function commandToggleDebug()
    {
        $this->debug = ! $this->debug;
        Session::put('debug', $this->debug);
        toast()->info($this->debug ? 'Debug activated' : 'Debug deactivated')->push();
        $this->resetInput();
    }

    protected function commandClearChat()
    {
        $this->messages = collect();
        Session::put('chat', $this->messages);
        $this->resetInput();
    }

    protected function commandAskAgent()
    {
        try {
            $history = ConversationBufferMemory::fromMessages($this->messages->toArray());
            $response = Mindwave::agent($history)->ask($this->draft);

            $this->messages->push([
                'role' => 'assistant',
                'content' => $response,
            ]);

        } catch (Throwable $exception) {
            toast()->danger('Chatbout could not respond');
            $this->messages->push([
                'role' => 'system',
                'content' => 'Error: '.$exception->getMessage(),
            ]);
        }
    }

    public function resetInput()
    {
        $this->reset('draft');
    }

    public function sendMessage()
    {
        if ($this->draft == '/clear') {
            return $this->commandClearChat();
        }

        if ($this->draft == '/debug') {
            return $this->commandToggleDebug();
        }

        $this->messages->push([
            'role' => 'user',
            'content' => $this->draft,
        ]);

        $this->commandAskAgent();
        Session::put('chat', $this->messages);
        $this->resetInput();
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}
