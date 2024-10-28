<?php

namespace App\Repositories;

use App\Interfaces\EmailContentRepositoryInterface;
use App\Models\EmailContent;
use Illuminate\Support\Facades\Auth;

class EmailContentRepository implements EmailContentRepositoryInterface
{
    protected $relations = [
        'user'
    ];

    public function getById($dataId)
    {
        return EmailContent::where("user_id", $dataId)->first();
    }

    public function update($dataId, $newDetailsData)
    {
        $newDetailsDataSave['user_id'] = Auth::user()->id;
        $newDetailsDataSave['body'] = $newDetailsData['auto_reply_body'];
        $newDetailsDataSave['cc'] =  $newDetailsData['auto_reply_cc'];
        $newDetailsDataSave['start_auto_replied'] =  $newDetailsData['start_date'];
        $newDetailsDataSave['end_auto_replied'] =  $newDetailsData['end_date'];

        if ($newDetailsData['auto_replied'] === "Aktif") {
            $newDetailsDataSave['auto_replied'] = 1;
        } else {
            $newDetailsDataSave['auto_replied'] = 0;
        }

        $id = EmailContent::find($dataId);
        $id->update($newDetailsDataSave);
    }
}
