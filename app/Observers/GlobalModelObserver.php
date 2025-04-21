<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class GlobalModelObserver
{
    /**
     * Handle the Model "creating" event.
     */
    public function creating(Model $model): void
    {
        // Automatically set UUID if 'id' column is UUID
        if (empty($model->id)) {
            $model->id = Str::uuid()->toString();
        }

        // Set created_by from authenticated user
        if (Auth::check()) {
            $model->created_by = Auth::id();
        }
    }

    /**
     * Handle the Model "updating" event.
     */
    public function updating(Model $model): void
    {
        // Set updated_by from authenticated user
        if (Auth::check()) {
            $model->updated_by = Auth::id();
        }
    }

    /**
     * Handle the Model "deleting" event.
     */
    public function deleting(Model $model): void
    {
        //
    }

    /**
     * Handle the Model "restored" event.
     */
    public function restoring(Model $model): void
    {
        //
    }

    /**
     * Handle the Model "force deleted" event.
     */
    public function forceDeleted(Model $model): void
    {
        //
    }
}
