<?php

namespace App\Interfaces;

interface EmailContentRepositoryInterface
{
    public function getById($dataId);
    public function update($dataId, $newDetailsData);
}
