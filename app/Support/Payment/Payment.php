<?php

namespace App\Support\Payment;

use App\Support\Payment\Interfaces\ProducePaymentInterface;

class Payment
{
    public function __construct(protected ProducePaymentInterface $payment)
    {

    }

    public function makePayment(array $data)
    {
        return $this->payment->execute($data);
    }

    public function getPaymentObj(): ProducePaymentInterface
    {
        return $this->payment;
    }
}
