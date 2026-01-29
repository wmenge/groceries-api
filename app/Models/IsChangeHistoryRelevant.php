<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Services\ChangeEventService;

// TODO: Split out into different parts to prevent always loading change history
// Or have lazy loading
trait IsChangeHistoryRelevant {

    protected static function booted(): void
    {
        // self::retrieved(static function (Model $model): void {
        //     $model->events = ChangeEventService::getChangeEvents($model);
        // });

        self::creating(static function (Model $model): void {
            $model->user()->associate(Auth::user());
        });

        self::saving(static function (Model $model): void {
            unset($model->events);
            ChangeEventService::storeChangeEvent($model);
        });
    }
    
}