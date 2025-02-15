<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\ShoppingList;
use Illuminate\Http\Request;
use App\Services\SortService;

class ShoppingListController extends Controller
{
    public function __construct(
        protected SortService $sortService,
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
        // TODO: map/validate/be generally very paranoid of user input!
        $shoppingList = new ShoppingList;
        $shoppingList->name = ucfirst($request->name);
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

        if (!empty($shoppingList)) {
            // TODO: map/validate/be generally very paranoid of user input!
            if (!is_null($request->name)) ucfirst($shoppingList->name = $request->name);
            $shoppingList->save();
            return response()->json($shoppingList);
        } else {
            return response()->json("ShoppingList not found", 404);
        }
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
