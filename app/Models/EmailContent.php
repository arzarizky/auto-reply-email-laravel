<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailContent extends Model
{
    use HasFactory;

    protected $table = 'email_contents';

    protected $fillable = [
        'user_id',
        'subject',
        'body',
        'cc',
        'auto_replied',
        'start_auto_replied',
        'end_auto_replied'
    ];

    public function user(): HasOne
    {
        return $this->HasOne(User::class, 'id', '	user_id');
    }
}
