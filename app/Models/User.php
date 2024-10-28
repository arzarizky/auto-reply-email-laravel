<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'avatar',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role(): HasOne
    {
        return $this->HasOne(Roles::class, 'id', 'role_id');
    }

    public function isAdmin() {
        return $this->role->name == "Admin";
    }

    public function isKaryawan() {
        return $this->role->name == "Karyawan";
    }

    public function getPicAvatarAdmin()
    {
        $url = url($this->avatar);
        $parsedUrl = parse_url($url);

        if (isset($parsedUrl['scheme']) && $parsedUrl['scheme'] === 'https') {
            return $url;
        } else {
            return url('images/picture/avatar/'.$this->avatar);
        }
    }
}
