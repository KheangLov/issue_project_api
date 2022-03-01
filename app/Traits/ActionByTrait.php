<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait ActionByTrait
{
    public static function BootActionByTrait()
    {
        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'created_by')) {
                $model->created_by = optional(auth()->user())->id;
            }
        });

        static::updating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'updated_by')) {
                $model->updated_by = optional(auth()->user())->id;
            }
        });

        static::deleting(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'deleted_by')) {
                $model->deleted_by = optional(auth()->user())->id;
            }
        });
    }
}
