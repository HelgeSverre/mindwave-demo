<?php

namespace App\Enums;

enum DocumentState: string
{
    case pending = 'pending';
    case consuming = 'consuming';
    case consumed = 'consumed';
    case empty = 'empty';
    case failed = 'failed';
    case unsupported = 'unsupported';

    public function label(): string
    {
        return match ($this) {
            self::pending => 'Pending',
            self::consuming => 'Consuming',
            self::consumed => 'Consumed',
            self::empty => 'Empty',
            self::failed => 'Failed',
            self::unsupported => 'Unsupported',
        };
    }
}
