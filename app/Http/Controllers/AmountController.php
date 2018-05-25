<?php

namespace App\Http\Controllers;

use App\Amount;
use Illuminate\Http\Request;

class AmountController extends Controller
{
    public function store(Request $request)
    {
        try {
            $amount = Amount::create([
                'quantity'   => $request->quantity,
                'price'      => $request->price,
                'product_id' => $request->product,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'data'    => $amount,
            'message' => message('MSG001'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $amount           = Amount::find($id);
            $amount->quantity = $request->quantity;
            $amount->price    = $request->price;
            $amount->save();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'data'    => $amount,
            'message' => message('MSG002'),
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $amount = Amount::find($id);
            $amount->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'message' => message('MSG003'),
        ], 200);
    }
}
