<?php

namespace App\Http\Controllers;

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
        if(array_is_list($request->all())) {

            $shoppingListEntries = array();

            foreach ($request->all() as $entry) {
                if (!$this->shoppingListEntryService->isValid((object)$entry)) {
                    return response()->json('invalid', 400);
                }
            }

            foreach ($request->all() as $entry) {
                $shoppingListEntries[] = $this->storeObject((object)$entry, $shoppingListId);
            }

            return response()->json($shoppingListEntries, 201);
        }

        return response()->json($this->storeObject((object)$request->all(), $shoppingListId), 201);
    }

    private function storeObject($data, int $shoppingListId) {

        if (!$this->shoppingListEntryService->isValid($data)) {
            return response()->json('invalid', 400);
        }

        $shoppingListEntry = empty($data->id) ? new ShoppingListEntry() : ShoppingListEntry::find($data->id);

        $shoppingListEntry = $this->shoppingListEntryService->map($data, $shoppingListEntry);
        $shoppingListEntry->shoppingList()->associate(ShoppingList::find($shoppingListId));
        $shoppingListEntry->save();

        return $shoppingListEntry;
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

    /**
     * Get all open entries from all shopping lists
     */
    public function openEntries()
    {
        $shoppingListEntries = ShoppingListEntry::where('status', '=', ShoppingListEntryStatusEnum::Open)->get()->load(['grocery']);
        return response()->json($shoppingListEntries);
    }
}
