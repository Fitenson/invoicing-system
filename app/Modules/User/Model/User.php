<?php

namespace App\Modules\User\Model;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected static function boot()
    {
        parent::boot();
        static::creating(function($model) {
            $model->id = Str::uuid()->toString();
            $User = Auth::user();
            if(!empty($User)) {
                $model->created_by = $User->id;
            }
        });

        static::updating(function($model) {
            $User = Auth::user();
            if(!empty($User)) {
                $model->updated_by = $User->id;
            }
        });
    }

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
}
