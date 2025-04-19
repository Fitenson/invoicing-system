<?php

namespace App\Modules\Project\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class Project extends Model
{
    use HasFactory;

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
        'client',
        'description',
        'rate_per_hour',
        'total_hours',
        'created_by',
        'updated_by'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
    ];
}
