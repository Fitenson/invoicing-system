<?php

namespace App\Modules\Invoice\Model;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class Invoice extends Model
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


    public function projects()
    {
        return $this->hasMany(InvoiceHasProjects::class, 'invoice');
    }
}
