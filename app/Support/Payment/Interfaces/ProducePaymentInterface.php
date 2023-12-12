<?php

namespace App\Support\Payment\Interfaces;

use Illuminate\Http\Request;

interface ProducePaymentInterface
{
    public function execute(array $data);
}
