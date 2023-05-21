<?php

namespace App\Jobs;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mindwave\Mindwave\Facades\DocumentLoader;
use Mindwave\Mindwave\Facades\Mindwave;
use Throwable;

class ConsumeDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Document $document;

    public $tries = 1;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function failed(Throwable $exception): void
    {
        $this->document->update(['state' => 'failed']);
    }

    public function handle(): void
    {
        if (! Str::endsWith($this->document->path, '.pdf')) {
            $this->fail();

            return;
        }

        $doc = DocumentLoader::fromPdf(
            Storage::get($this->document->path),
            meta: [
                'id' => $this->document->id,
                'filename' => $this->document->filename,
            ]
        );

        if ($doc->isEmpty()) {
            $this->document->update(['state' => 'empty']);
        }

        $this->document->update([
            'text' => $doc->content(),
            'state' => 'consuming',
        ]);

        Mindwave::brain()->consume($doc);

        $this->document->update(['state' => 'consumed']);
    }
}
