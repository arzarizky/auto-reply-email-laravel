<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MailServer extends Model
{
    use HasFactory;

    protected $table = 'mail_servers';

    protected $fillable = [
        'user_id',
        'host',
        'port',
        'encryption',
        'username',
        'password',
    ];

    public function user(): HasOne
    {
        return $this->HasOne(User::class, 'id', '	user_id');
    }
}
