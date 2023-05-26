<?php

namespace App\Models;

use App\Enums\DocumentState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class Document extends Model
{
    protected $guarded = [];

    protected $casts = [
        'state' => DocumentState::class,
    ];

    protected static function boot()
    {
        parent::boot();

        self::deleting(fn (Document $document) => Storage::delete($document->path));
    }

    public function scopeInState(Builder $q, DocumentState $documentState): void
    {
        $q->where('state', $documentState);
    }

    public function scopeNotInState(Builder $q, DocumentState $documentState): void
    {
        $q->where('state', '!=', $documentState);
    }

    /**
     * @param array{
     *     name: string,
     *     content_type: string,
     *     key: string,
     *     uuid: string,
     *     bucket: string,
     *     visibility: string
     * } $s3
     */
    public static function createFromS3(array $s3): ?self
    {
        if (Arr::has($s3, ['key', 'name']) === false) {
            throw new InvalidArgumentException('Invalid file was uploaded');
        }

        $path = sprintf('documents/%s/%s', time(), Str::replace(' ', '-', $s3['name']));

        if (Storage::copy($s3['key'], $path) === false) {
            return null;
        }

        return self::create([
            'path' => $path,
            'filename' => $s3['name'],
            'mime' => $s3['content_type'] ?? null,
            'size' => rescue(fn () => Storage::size($path), null, false),
        ]);
    }
}
