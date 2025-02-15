<?php

namespace App\Http\Controllers;

use App\Services\GroceryService;
use App\Models\ShoppingListEntryStatusEnum;
use App\Models\ShoppingListEntry;
use App\Models\ShoppingList;
use App\Models\Grocery;

use Illuminate\Http\Request;

class ShoppingListEntryController extends Controller
{
    public function __construct(
        protected GroceryService $groceryService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(ShoppingListEntry::all()->load(['grocery']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $shoppingListId)
    {
        $shoppingList = ShoppingList::find($shoppingListId);
        $grocery = $this->groceryService->getOrCreateGroceryByName(ucfirst($request->groceryName));

        // TODO: map/validate/be generally very paranoid of user input!
        $shoppingListEntry = new ShoppingListEntry;
        $shoppingListEntry->quantity = $request->quantity;
        $shoppingListEntry->shoppingList()->associate($shoppingList);
        $shoppingListEntry->grocery()->associate($grocery);
        
        $shoppingListEntry->save();
        return response()->json($shoppingListEntry, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $shoppingListId, int $id)
    {
        $shoppingListEntry = ShoppingListEntry::find($id)->load(['grocery']);

        if (!empty($shoppingListEntry)) {
            return response()->json($shoppingListEntry);
        } else {
            return response()->json("ShoppingListEntry not found", 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $shoppingListId, int $id)
    {
        $shoppingListEntry = ShoppingListEntry::find($id);
        $shoppingList = ShoppingList::find($shoppingListId);

        if (!empty($shoppingListEntry)) {
            // TODO: map/validate/be generally very paranoid of user input!
            
            if (!is_null($request->quantity)) $shoppingListEntry->quantity = $request->quantity;
            $shoppingListEntry->shoppingList()->associate($shoppingList);

            if ($request->groceryName) {
                $grocery = $this->groceryService->getOrCreateGroceryByName(ucfirst($request->groceryName));
                $shoppingListEntry->grocery()->associate($grocery);
            }

            if (!is_null($request->status)) $shoppingListEntry->status = ShoppingListEntryStatusEnum::from($request->status);
        
            $shoppingListEntry->save();
            // TODO: This doesnt set response code of request
            return response()->json($shoppingListEntry);
        } else {
            return response()->json("ShoppingListEntry not found", 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $shoppingListId, int $id)
    {
        $shoppingListEntry = ShoppingListEntry::find($id);

        if (!empty($shoppingListEntry)) {
            $shoppingListEntry->delete();
            return response()->json("ShoppingListEntry deleted", 202);
        } else {
            return response()->json("ShoppingListEntry not found", 404);
        }
    }
}
