<?php

namespace App\Repositories\Interfaces;

interface AccessedAppRepositoryInterface
{
    public function create($fields);
    public function all();
}