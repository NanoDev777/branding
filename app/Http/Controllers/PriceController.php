<?php

namespace App\Http\Controllers;

use App\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index(Request $request)
    {
        $rowsPerPage = 10;

        if ($request->has('rowsPerPage')) {
            $rowsPerPage = $request->input('rowsPerPage');
        }

        $prices = Price::orderBy('id', 'DESC');

        if ($request->has('filter')) {
            $filter = $request->input('filter');

            $prices = $prices->where(function ($query) use ($filter) {
                $query->where('quantity', 'LIKE', "%" . $filter . "%")
                    ->orWhere('logo', 'LIKE', "%" . $filter . "%")
                    ->orWhere('utility', 'LIKE', "%" . $filter . "%");
            });
        }

        $prices = $prices->paginate($rowsPerPage);

        $response = [
            'prices' => $prices,
            'params' => [
                'total'        => $prices->total(),
                'current_page' => $prices->currentPage(),
                'per_page'     => $prices->perPage(),
            ],
        ];

        return response()->json($response);
    }

    public function show($id)
    {
        try {
            $price = Price::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => message('MSG011')], 404);
        }
        return response()->json([
            'success' => true,
            'data'    => $price,
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $price = Price::create([
                'quantity' => $request->quantity,
                'logo'     => $request->logo,
                'utility'  => $request->utility,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG011')], 500);
        }
        return response()->json(['message' => message('MSG001')], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $price           = Price::find($id);
            $price->quantity = $request->quantity;
            $price->logo     = $request->logo;
            $price->utility  = $request->utility;
            $price->save();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json(['message' => message('MSG002')], 200);
    }

    public function destroy($id)
    {
        try {
            $price = Price::find($id);
            $price->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json(['message' => message('MSG003')], 200);
    }
}
