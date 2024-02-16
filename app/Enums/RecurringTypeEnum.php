<?php

namespace App\Enums;

enum RecurringTypeEnum: int
{
    case Month3      = 3;
    case Month6      = 6;
    case Month12     = 12;
    case Month24     = 24;
    case Continuous  = 0;
}
