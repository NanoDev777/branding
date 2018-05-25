<?php

namespace App\Http\Controllers;

use App\Packing;
use Illuminate\Http\Request;

class PackingController extends Controller
{

    public function store(Request $request)
    {
        try {
            $packing = Packing::create([
                'width'      => $request->width,
                'height'     => $request->height,
                'thickness'  => $request->thickness,
                'weight'     => $request->weight,
                'box'        => $request->box,
                'product_id' => $request->product,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'data'    => $packing,
            'message' => message('MSG001'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $packing            = Packing::find($id);
            $packing->width     = $request->width;
            $packing->height    = $request->height;
            $packing->thickness = $request->thickness;
            $packing->weight    = $request->weight;
            $packing->box       = $request->box;
            $packing->save();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'data'    => $packing,
            'message' => message('MSG002'),
        ], 200);
    }
}
