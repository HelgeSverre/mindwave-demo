<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $guarded = [];

    public function scopeConsumed(Builder $q): void
    {
        $q->where('state', 'consumed');
    }

    public function scopePending(Builder $q): void
    {
        $q->where('state', 'pending');
    }
}
