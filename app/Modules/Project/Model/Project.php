<?php

namespace App\Modules\Project\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Common\Model\BaseModel;


class Project extends BaseModel
{
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
}
