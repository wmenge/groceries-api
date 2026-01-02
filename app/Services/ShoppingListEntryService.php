<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Grocery;
use App\Models\ShoppingListEntryStatusEnum;

class ShoppingListEntryService
{
    public static function isValid($data)
    {
        return
            is_object($data) &&
            is_scalar($data->groceryName) &&
            !empty($data->groceryName) &&
            (empty($data->id) || is_numeric($data->id)) &&
            Grocery::where('name', ucfirst(htmlentities($data->groceryName, ENT_QUOTES, 'UTF-8'))) && 
            is_numeric($data->quantity) &&
            !empty($data->quantity);
            is_scalar($data->status) &&
            !empty($data->status) &&
            ShoppingListEntryStatusEnum::from($data->status) instanceof ShoppingListEntryStatusEnum;    
    }

    public function map($data, $object)
    {
        // Explicitly map parameters, be paranoid of your input
        // https://phpbestpractices.org
        if (ShoppingListEntryService::isValid($data)) {
            if (!empty($data->id)) {
               $object->id = filter_var($data->id, FILTER_SANITIZE_NUMBER_INT); 
            }
            $object->quantity = filter_var($data->quantity, FILTER_SANITIZE_NUMBER_INT);
            $grocery = Grocery::firstOrNew(['name' => ucfirst(htmlentities($data->groceryName, ENT_QUOTES, 'UTF-8'))]);
            if (!$grocery->exists) {
                $grocery->user()->associate(Auth::user());
                $grocery->save();
            }
            $object->grocery()->associate($grocery);
            $object->status = ShoppingListEntryStatusEnum::from($data->status);
        }

        return $object;
    }


}

