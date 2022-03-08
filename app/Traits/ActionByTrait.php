<?php

namespace App\Traits;

use App\Models\User;
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
    }

    public function createdBy() {
        if (!Schema::hasColumn($this->getTable(), 'created_by')) {
            return false;
        }
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy() {
        if (!Schema::hasColumn($this->getTable(), 'updated_by')) {
            return false;
        }
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy() {
        if (!Schema::hasColumn($this->getTable(), 'deleted_by')) {
            return false;
        }
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function getCreatedByFullNameAttribute() {
        return optional($this->createdBy)->name;
    }

    public function getUpdatedByFullNameAttribute() {
        return optional($this->updatedBy)->name;
    }

    public function getDeletedByFullNameAttribute() {
        return optional($this->deletedBy)->name;
    }
}
