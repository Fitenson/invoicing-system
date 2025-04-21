<?php

namespace App\Common\Model;

use Illuminate\Database\Eloquent\Model;

use App\Observers\GlobalModelObserver;

class BaseModel extends Model {
    protected static function booted()
    {
        static::observe(GlobalModelObserver::class);
    }
}
