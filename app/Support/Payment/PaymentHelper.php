<?php

namespace App\Support\Payment;

class PaymentHelper
{
    public static function formatLog(array  $input, string|int $line = '', string $function = '',
                                     string $class = ''): array
    {
        return array_merge($input, [
            'user_id' => auth()->check() ? auth()->id : 0,
            'id' => request()->id(),
            'line' => $line,
            'function' => $function,
            'class' => $class,
            'userAgent' => request()->header('User-Agent'),
        ]);
    }
}
