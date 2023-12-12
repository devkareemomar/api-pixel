<?php

namespace App\Interfaces;

interface NewsInterface
{
    public function news($request);

    public function news_details($request, $news_id);
}
