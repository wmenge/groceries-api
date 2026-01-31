<?php

namespace App\Http\Controllers;

use App\Models\Grocery;
use Illuminate\Http\Request;
use App\Services\GroceryService;
use App\Services\SortService;
use Illuminate\Support\Facades\DB;

class GroceryController
{
    public function __construct(
        protected SortService $sortService,
        protected GroceryService $groceryService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Grocery::query()->select('groceries.*');
        
        
        $search = $request->query('query');
        if ($search) {
            $query = $query->where('name','LIKE', '%' . $search . '%');
        }

        $order = $request->query('sort');
        if ($order) {
            $this->sortService->addSortExpression($query, $order);
        }

        $query = $query->addSelect(DB::raw('(select count(*) from shopping_list_entries where grocery_id = groceries.id) as entriesCount'));

        return $query->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->groceryService->isValid($request)) {
            return response()->json('invalid', 400);
        }

        $grocery = $this->groceryService->map($request, new Grocery);
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

        if (empty($grocery)) {
            return response()->json("Grocery not found", 404);
        }

        if (!$this->groceryService->isValid($request)) {
            return response()->json('invalid', 400);
        }

        $grocery = $this->groceryService->map($request, $grocery);
        $grocery->save();

        return response()->json($grocery, 200);
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
