<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $rowsPerPage = 10;

        if ($request->has('rowsPerPage')) {
            $rowsPerPage = $request->input('rowsPerPage');
        }

        $categories = Category::orderBy('id', 'DESC');

        if ($request->has('filter')) {
            $filter = $request->input('filter');

            $categories = $categories->where(function ($query) use ($filter) {
                $query->where('name', 'LIKE', "%" . $filter . "%");
            });
        }

        $categories = $categories->paginate($rowsPerPage);

        $response = [
            'categories' => $categories,
            'params'     => [
                'total'        => $categories->total(),
                'current_page' => $categories->currentPage(),
                'per_page'     => $categories->perPage(),
            ],
        ];

        return response()->json($response);
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => message('MSG011')], 404);
        }
        return response()->json([
            'success' => true,
            'data'    => $category,
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $category = Category::create([
                'name'        => $request->name,
                'description' => $request->description,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG011')], 500);
        }
        return response()->json(['message' => message('MSG001')], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $category              = Category::find($id);
            $category->name        = $request->name;
            $category->description = $request->description;
            $category->save();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json(['message' => message('MSG002')], 200);
    }

    public function destroy($id)
    {
        try {
            $category = Category::find($id);
            $category->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json(['message' => message('MSG003')], 200);
    }

    function list() {
        $categories = Category::orderBy('id', 'DESC')->get();
        return response()->json(['data' => $categories], 200);
    }
}
