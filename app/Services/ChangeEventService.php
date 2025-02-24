<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\ChangeEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ChangeEventService
{
    public static function storeChangeEvent($object)
    {
        if (!($object instanceof Model) || !$object->isDirty() || !$object->exists) return;
        
        $event = new ChangeEvent();
        $event->table = $object->getTable();
        $event->object_id = $object->id;
        $event->changed = json_encode($object->getDirty());
        $event->user()->associate(Auth::user());
        $event->save();
    }

    public static function getChangeEvents($object) {
        $log = [];

        $events = ChangeEvent::where('table', $object->getTable())->where('object_id', $object->id)->get()->load(['user']);

        // creation event
        $creationEvent = (object)array( 
            "type" => "CREATE", 
            "time" => $object->created_at,
            "user" => $object->user
        );

        $log[] = $creationEvent;

        foreach ($events as $event) {
            $updateEvent = (object)array( 
                "type" => "UPDATE", 
                "time" => $event->created_at,
                "changed" => json_decode($event->changed),
                "user" => $event->user
            );
    
            $log[] = $updateEvent;
        }

        return $log;
    }
}