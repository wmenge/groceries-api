<?php

namespace App\Services;

use App\Models\Grocery;

class GroceryService //extends Service
{
    // replace by Eloquent firstOrCreate
    // https://laravel.com/docs/11.x/eloquent#retrieving-or-creating-models
    public function getOrCreateGroceryByName($name) {
        $grocery = Grocery::where('name', $name)->first();

        if (!$grocery) {
            $grocery = new Grocery();
            $grocery->name = $name;
            $grocery->save();
        }

        return $grocery;
    }

}

