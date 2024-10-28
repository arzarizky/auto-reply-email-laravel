<?php

namespace App\Interfaces;

interface MailServerRepositoryInterface
{
    public function getById($dataId);
    public function update($dataId, $newDetailsData);
}
