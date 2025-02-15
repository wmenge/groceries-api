<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Grocery;
use Illuminate\Http\Request;
use App\Services\SortService;

class GroceryController extends Controller
{
    public function __construct(
        protected SortService $sortService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Grocery::query();
        
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
