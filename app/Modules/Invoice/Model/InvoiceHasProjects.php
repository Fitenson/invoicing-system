<?php

namespace App\Modules\Invoice\Model;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Modules\User\Model\User;
use App\Modules\Project\Model\Project;


class InvoiceHasProjects extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

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
        'invoice',
        'project',
        'created_by',
        'updated_by'
    ];


    public function client()
    {
        return $this->belongsTo(User::class, 'client');
    }


    public function project()
    {
        return $this->belongsTo(Project::class, 'project');
    }
}
