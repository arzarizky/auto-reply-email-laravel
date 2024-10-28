<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EmailReceived extends Model
{
    use HasFactory;

    protected $table = 'email_receiveds';

    protected $fillable = [
        'user_id',
        'message_id',
        'from_email',
        'from_name',
        'subject',
        'body',
        'received_at'
    ];

    public function user(): HasOne
    {
        return $this->HasOne(User::class, 'id', 'user_id');
    }

    public function emailReply(): HasOne
    {
        return $this->HasOne(EmailReply::class, 'received_email_id', 'id');
    }
}
