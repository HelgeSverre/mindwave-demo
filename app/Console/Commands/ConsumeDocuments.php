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
        Document::pending()->get()->each(function (Document $document) {
            ConsumeDocument::dispatch($document);
        });
    }
}
