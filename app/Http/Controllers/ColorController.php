<?php

namespace App\Http\Controllers;

use App\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        try {
            $colors = Color::orderBy('id', 'DESC')->get();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'data'    => $colors,
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $color = Color::create([
                'name' => $request->name,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'data'    => $color,
            'message' => message('MSG001'),
        ], 200);
    }
}
