<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Services\GroceryService;
use App\Models\ShoppingListEntryStatusEnum;
use App\Models\ShoppingListEntry;
use App\Models\ShoppingList;
use App\Models\Grocery;

use App\Services\ShoppingListEntryService;

use Illuminate\Http\Request;

class ShoppingListEntryController extends Controller
{
    public function __construct(
        protected GroceryService $groceryService,
        protected ShoppingListEntryService $shoppingListEntryService
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
        if (!$this->shoppingListEntryService->isValid($request)) {
            return response()->json('invalid', 400);
        }
        
        $shoppingListEntry = $this->shoppingListEntryService->map($request, new ShoppingListEntry);
        $shoppingListEntry->shoppingList()->associate(ShoppingList::find($shoppingListId));
        $shoppingListEntry->user()->associate(Auth::user());
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

        if (empty($shoppingList)) {
            return response()->json("Shopping List not found", 404);
        }

        if (empty($shoppingListEntry)) {
            return response()->json("Shopping List Entry not found", 404);
        }

        if (!$this->shoppingListEntryService->isValid($request)) {
            return response()->json('invalid', 400);
        }
        
        $shoppingListEntry = $this->shoppingListEntryService->map($request, $shoppingListEntry);
        $shoppingListEntry->shoppingList()->associate($shoppingList);
        
        $shoppingListEntry->save();        

        return response()->json($shoppingListEntry, 200);
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
