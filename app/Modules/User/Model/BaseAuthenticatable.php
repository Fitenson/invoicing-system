<?php

namespace App\Modules\User\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Common\Model\BaseModel;
use App\Observers\GlobalModelObserver;


class BaseAuthenticatable extends Authenticatable {
    protected static function booted()
    {
        parent::boot();
        static::observe(GlobalModelObserver::class);
    }
}
