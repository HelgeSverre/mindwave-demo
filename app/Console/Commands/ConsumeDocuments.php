<?php

namespace App\Console\Commands;

use App\Enums\DocumentState;
use App\Jobs\ConsumeDocument;
use App\Models\Document;
use Illuminate\Console\Command;

class ConsumeDocuments extends Command
{
    protected $signature = 'documents:consume';

    public function handle()
    {
        Document::whereNotIn('state', [
            DocumentState::consumed,
            DocumentState::consuming,
        ])->get()->each(function (Document $document) {
            $this->info("Queueing consumption of : {$document->id} - {$document->filename}");
            ConsumeDocument::dispatch($document);
        });
    }
}
