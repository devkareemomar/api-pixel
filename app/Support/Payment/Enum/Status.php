<?php

namespace App\Support\Payment\Enum;

enum Status: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILEd = 'failed';
}
