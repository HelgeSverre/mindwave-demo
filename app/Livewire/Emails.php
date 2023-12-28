<?php

namespace App\Livewire;

use App\Models\Email;
use HelgeSverre\Brain\Facades\Brain;
use HelgeSverre\Milvus\Facades\Milvus;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class Emails extends Component
{
    #[Url('search')]
    public string $search = '';

    #[Computed]
    public function searchEmbedding()
    {
        if (! $this->search) {
            return null;
        }

        return Brain::embedding($this->search);
    }

    public function render()
    {
        $extra = [];

        $ids = [];
        if ($this->searchEmbedding()) {
            $result = Milvus::vector()->search(
                collectionName: 'emails',
                vector: $this->searchEmbedding(),
                limit: 5,
                outputFields: [
                    'from',
                    'subject',
                ],
            );

            $extra = $result->collect('data');
            $ids = $result->collect('data')->pluck('id')->all();
        }

        // TODO: Sort the emails based on the order of the ids
        return view('livewire.emails', [
            'extra' => $extra,
            'emails' => $ids
                ? Email::query()
                    ->whereIn('vector_db_id', $ids)
                    ->orderByRaw(sprintf('FIELD(vector_db_id, %s) ASC', implode(', ', array_map(fn ($id) => Str::wrap($id, "'", "'"), $ids))))
                    ->get()
                : Email::query()
                    ->latest()
                    ->get(),
        ]);
    }
}
