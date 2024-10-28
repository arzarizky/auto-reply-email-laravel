<?php

namespace App\Interfaces;

interface EmailRecievedRepositoryInterface
{
    public function getAllById($userId, $search, $perPage);
}
