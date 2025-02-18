<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\ShoppingList;
use Illuminate\Http\Request;
use App\Services\ShoppingListService;
use App\Services\SortService;

class ShoppingListController extends Controller
{
    public function __construct(
        protected SortService $sortService,
        protected ShoppingListService $shoppingListService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // TODO: Move logic to service, valid for all items        
        $query = ShoppingList::query()->with('entries.grocery');

        // TODO: Generalize searching (?name= instead of query, prevent clash with sort param)
        $search = $request->query('query');
        if ($search) {
            $query = $query->where('name','LIKE', '%' . $search . '%');
        }

        $order = $request->query('sort');
        if ($order) {
            $this->sortService->addSortExpression($query, $order);
        }

        return $query->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->shoppingListService->isValid($request)) {
            return response()->json('invalid', 400);
        }

        $shoppingList = $this->shoppingListService->map($request, new ShoppingList);
        $shoppingList->save();
        
        return response()->json($shoppingList, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $shoppingList = ShoppingList::find($id);

        $shoppingList->load('entries.grocery');

        if (!empty($shoppingList)) {
            return response()->json($shoppingList);
        } else {
            return response()->json("ShoppingList not found", 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $shoppingList = ShoppingList::find($id);

        if (empty($shoppingList)) {
            return response()->json("Shopping List not found", 404);
        }

        if (!$this->shoppingListService->isValid($request)) {
            return response()->json('invalid', 400);
        }

        $shoppingList = $this->shoppingListService->map($request, $shoppingList);
        $shoppingList->save();
        return response()->json($grocery, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $shoppingList = ShoppingList::find($id);

        if (!empty($shoppingList)) {
            $shoppingList->delete();
            return response()->json("ShoppingList deleted", 202);
        } else {
            return response()->json("ShoppingList not found", 404);
        }
    }
}
