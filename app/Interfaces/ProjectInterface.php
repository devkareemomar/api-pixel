<?php

namespace App\Interfaces;

interface ProjectInterface
{
    public function project($request);

    public function project_details($request, $project_id);
}
