<?php

namespace App\Models;

use App\Services\ChangeEventService;

trait IsChangeHistoryRelevant {

    public function save(array $options = []) {
        ChangeEventService::storeChangeEvent($this);
        return parent::save($options);
    }
    
}