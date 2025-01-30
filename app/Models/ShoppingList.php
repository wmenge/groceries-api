<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
    public function entries(): HasMany
    {
        return $this->hasMany(ShoppingListEntry::class);
    }
}
