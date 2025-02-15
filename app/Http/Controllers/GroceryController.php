<?php

namespace App\Http\Controllers;

use App\Models\Grocery;
use Illuminate\Http\Request;

class GroceryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->query('query');
        if ($query) {
            return Grocery::where('name','LIKE', '%' . $query . '%')->get();
        } else {
            return response()->json(Grocery::all());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: map/validate/be generally very paranoid of user input!
        // TODO: Find existing case insensitive and update that instead
        $grocery = new Grocery;
        $grocery->name = ucfirst($request->name);
        $grocery->save();
        return response()->json($grocery, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $grocery = Grocery::find($id);

        if (!empty($grocery)) {
            return response()->json($grocery);
        } else {
            return response()->json("Grocery not found", 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $grocery = Grocery::find($id);

        if (!empty($grocery)) {
            // TODO: map/validate/be generally very paranoid of user input!
            if (!is_null($request->name)) $grocery->name = ucfirst($request->name);
            $grocery->save();
            return response()->json($grocery, 200);
        } else {
            return response()->json("Grocery not found", 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $grocery = Grocery::find($id);

        if (!empty($grocery)) {
            $grocery->delete();
            return response()->json("Grocery deleted", 202);
        } else {
            return response()->json("Grocery not found", 404);
        }
    }
}
