<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailReply extends Model
{
    use HasFactory;

    protected $table = 'email_replys';

    protected $fillable = [
        'user_id',
        'received_email_id',
        'to_email',
        'subject',
        'body',
        'cc',
        'success',
        'replied_at'
    ];
}
