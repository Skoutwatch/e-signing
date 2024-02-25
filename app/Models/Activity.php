<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as ModelsActivity;

class Activity extends ModelsActivity
{
    use HasFactory, HasUuids;

    // protected static $logAttributes = [];

    // public static function boot()
    // {
    //     parent::boot();

    //     static::saving(function (ModelsActivity $model) {
    //         static::$logAttributes = array_keys($model->getDirty());
    //     });
    // }

    // public static function activity(string $logName = null): ActivityLogger
    // {
    //     $defaultLogName = config('activitylog.default_log_name');

    //     $logStatus = app(ActivityLogStatus::class);

    //     return app(ActivityLogger::class)
    //         ->useLog($logName ?? $defaultLogName)
    //         ->setLogStatus($logStatus);
    // }
}
