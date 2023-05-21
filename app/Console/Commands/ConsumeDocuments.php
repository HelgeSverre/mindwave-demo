<?php

namespace App\Console\Commands;

use App\Jobs\ConsumeDocument;
use App\Models\Document;
use Illuminate\Console\Command;

class ConsumeDocuments extends Command
{
    protected $signature = 'documents:consume';

    public function handle()
    {

        $documents = Document::query()->where('state', '!=', 'consumed')->get();

        foreach ($documents as $document) {
            ConsumeDocument::dispatch($document);
        }

    }
}
