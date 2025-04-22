<?php

namespace App\Modules\Project\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Common\Model\BaseModel;

use App\Modules\Project\Factory\ProjectFactory;


class Project extends BaseModel
{
    protected $keyType = 'string';
    public $incrementing = false;


    use HasFactory;

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


    public static function newFactory()
    {
        return ProjectFactory::new();
    }
}
