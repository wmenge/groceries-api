<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoppingListEntry extends Model
{
    use IsChangeHistoryRelevant;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The model's default values for attributes.
     */
    protected $attributes = [
        'quantity' => 1,
        'status' => ShoppingListEntryStatusEnum::Open,
    ];

    protected $casts = [
		'status' => ShoppingListEntryStatusEnum::class,
  	];
    
    public function shoppingList(): BelongsTo
    {
        return $this->belongsTo(ShoppingList::class);
    }

    public function grocery(): BelongsTo
    {
        return $this->belongsTo(Grocery::class);
    }
}
