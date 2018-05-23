<?php

namespace App\Http\Controllers;

use App\Cost;
use Illuminate\Http\Request;

class CostController extends Controller
{
    public function index()
    {
        $costs = Cost::all()->first();
        if (is_null($costs)) {
            return response()->json(['message' => message('MSG011')], 404);
        } else {
            return response()->json([
                'success' => true,
                'data'    => $costs,
            ], 200);
        }

    }

    public function update(Request $request, $id)
    {
        try {
            $costs = Cost::find($id);
            $costs->update($request->all());
            $costs->save();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'data'    => $costs,
            'message' => message('MSG002'),
        ]);
    }
}
