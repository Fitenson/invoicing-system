<?php

namespace App\Modules\Invoice\Model;

use App\Modules\User\Model\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Common\Model\BaseModel;


class Invoice extends BaseModel
{
    public $incrementing = false;
    protected $keyType = 'string';

    use HasFactory;


    protected static function boot()
    {
        parent::boot();

        // Delete related InvoiceHasProjects before deleting invoice
        static::deleting(function ($model) {
            $model->projects()->delete();
        });
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_number',
        'client',
        'description',
        'created_by',
        'updated_by'
    ];


    public function client()
    {
        return $this->belongsTo(User::class, 'client');
    }


    public function projects()
    {
        return $this->hasMany(InvoiceHasProjects::class, 'invoice');
    }
}
