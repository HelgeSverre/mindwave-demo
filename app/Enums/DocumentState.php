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

}
