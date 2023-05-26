<?php

namespace App\Models;

use App\Enums\DocumentState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $guarded = [];

    protected $casts = [
        'state' => DocumentState::class,
    ];

    public function scopeInState(Builder $q, DocumentState $documentState): void
    {
        $q->where('state', $documentState);
    }
}
