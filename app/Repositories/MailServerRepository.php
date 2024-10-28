<?php

namespace App\Repositories;

use App\Interfaces\MailServerRepositoryInterface;
use App\Models\MailServer;
use App\Models\EmailContent;
use Illuminate\Support\Facades\Auth;


class MailServerRepository implements MailServerRepositoryInterface
{
    protected $relations = [
        'user'
    ];

    public function getById($dataId)
    {
        return MailServer::where("user_id", $dataId)->first();
    }

    public function update($dataId, $newDetailsData)
    {
        $id = MailServer::find($dataId);
        $id->update($newDetailsData);
    }
}
