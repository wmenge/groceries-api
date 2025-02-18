<?php

namespace App\Services;

class ShoppingListService
{
    public static function isValid($data)
    {
        return
            is_object($data) &&
            //property_exists($data, 'name') &&
            is_scalar($data->name) &&
            !empty($data->name);
    }

    public function map($data, $object)
    {
        // Explicitly map parameters, be paranoid of your input
        // https://phpbestpractices.org
        if (ShoppingListService::isValid($data)) {
            $object->name = ucfirst(htmlentities($data->name, ENT_QUOTES, 'UTF-8'));
        }

        return $object;
    }


}

