<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoppingListEntry extends Model
{
    //

    public function shoppingList(): BelongsTo
    {
        return $this->belongsTo(ShoppingList::class);
    }

    public function grocery(): BelongsTo
    {
        return $this->belongsTo(Grocery::class);
    }
}
