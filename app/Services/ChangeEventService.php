<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\ChangeEvent;

class ChangeEventService
{
    public static function storeChangeEvent($object)
    {
        if (!($object instanceof Model) || !$object->isDirty() || !$object->exists) return;
        
        $event = new ChangeEvent();
        $event->table = $object->getTable();
        $event->object_id = $object->id;
        $event->changed = json_encode($object->getDirty());
        $event->save();
    }
}