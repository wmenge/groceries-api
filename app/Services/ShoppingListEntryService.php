<?php

namespace App\Services;

use App\Models\Grocery;
use App\Models\ShoppingListEntryStatusEnum;

class ShoppingListEntryService
{
    public static function isValid($data)
    {
        return
            is_object($data) &&
            //property_exists($data, 'groceryName') &&
            is_scalar($data->groceryName) &&
            !empty($data->groceryName) &&
            Grocery::where('name', ucfirst(htmlentities($data->groceryName, ENT_QUOTES, 'UTF-8'))) && 
            //property_exists($data, 'quantity') &&
            is_numeric($data->quantity) &&
            !empty($data->quantity);
            //property_exists($data, 'status') &&
            is_scalar($data->status) &&
            !empty($data->status) &&
            ShoppingListEntryStatusEnum::from($data->status) instanceof ShoppingListEntryStatusEnum;    
    }

    public function map($data, $object)
    {
        //\Log::info(print_r($data));
        \Log::info('Hallo!');

        \Log::info('This is some useful information.');
        // Explicitly map parameters, be paranoid of your input
        // https://phpbestpractices.org
        if (ShoppingListEntryService::isValid($data)) {
            $object->quantity = filter_var($data->quantity, FILTER_SANITIZE_NUMBER_INT);
            $grocery = Grocery::firstOrCreate(['name' => ucfirst(htmlentities($data->groceryName, ENT_QUOTES, 'UTF-8'))]);
            $object->grocery()->associate($grocery);
            $object->status = ShoppingListEntryStatusEnum::from($data->status);
        }

        return $object;
    }


}

