<?php

namespace App\Modules\User\Model;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Modules\User\Model\BaseAuthenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Laravel\Sanctum\HasApiTokens;
use App\Modules\User\Factory\UserFactory;


class User extends BaseAuthenticatable
{
    protected $keyType = 'string';
    public $incrementing = false;

    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'full_name',
        'phone_number',
        'company',
        'address',
        'password',
        'created_by',
        'updated_by'
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
        'id' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public static function newFactory()
    {
        return UserFactory::new();
    }
}
