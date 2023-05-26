<?php

namespace App\Jobs;

use App\Enums\DocumentState;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
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
        $this->document->update(['state' => DocumentState::failed]);
    }

    public function handle(): void
    {
        $meta = [
            'id' => $this->document->id,
            'filename' => $this->document->filename,
            'mime' => $this->document->mime,
            'size' => $this->document->size,
        ];

        $content = Storage::get($this->document->path);

        /** @noinspection PhpDuplicateMatchArmBodyInspection */
        $doc = match ($this->document->mime) {
            'text/plain' => DocumentLoader::fromText(Storage::get($this->document->path), $meta),
            'text/html' => DocumentLoader::fromHtml($content, $meta),
            'application/pdf' => DocumentLoader::fromPdf($content, $meta),
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => DocumentLoader::fromWord($content, $meta),

            // TODO: Implement loaders
            'text/csv' => null, // treat as text?
            'text/xml' => null, // treat as text?
            'text/calendar' => null,
            'application/vnd.ms-excel' => null,
        };

        if ($doc === null) {
            $this->document->update(['state' => DocumentState::unsupported]);

            return;
        }

        $this->document->update(
            $doc->isEmpty()
                ? ['state' => DocumentState::empty]
                : ['state' => DocumentState::consuming, 'text' => $doc->content()]
        );

        Mindwave::brain()->consume($doc);

        $this->document->update(['state' => DocumentState::consumed]);
    }
}
