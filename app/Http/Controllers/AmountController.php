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
                'quantity' => $request->quantity,
                'price'    => $request->price,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG011')], 500);
        }
        return response()->json(['message' => message('MSG001')], 200);
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
        return response()->json(['message' => message('MSG002')], 201);
    }
}
