<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use Illuminate\Http\Request;

class ShoppingListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(ShoppingList::all()->load('entries.grocery'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: map/validate/be generally very paranoid of user input!
        $shoppingList = new ShoppingList;
        $shoppingList->name = $request->name;
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
            if (!is_null($request->name)) $shoppingList->name = $request->name;
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
