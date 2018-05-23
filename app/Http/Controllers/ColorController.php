<?php

namespace App\Http\Controllers;

use App\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::orderBy('id', 'DESC')->get();
        if (is_null($colors)) {
            return response()->json(['message' => message('MSG011')], 404);
        } else {
            return response()->json(['data' => $colors], 200);
        }

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $color = Color::create([
            'name' => $request->name,
        ]);
        return response()->json([
            'success' => true,
            'data'    => $color,
            'message' => message('MSG001'),
        ], 200);
    }
}
