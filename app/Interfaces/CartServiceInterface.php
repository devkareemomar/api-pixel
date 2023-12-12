<?php

namespace App\Interfaces;

interface CartServiceInterface
{
    public function projects($request, $id);

    public function add($request);
}
