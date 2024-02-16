<?php

namespace App\Enums;

enum RecurringEnum: string
{
    case Daily   = 'daily';
    case Weekly  = 'weekly';
    case Monthly = 'monthly';
    case Yearly  =  'yearly';
}
