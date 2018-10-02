<?php

namespace App\Http\Controllers;

use App\Quotation;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    protected $quotations;

    public function __construct(Quotation $quotations)
    {
        $this->quotations = $quotations;
    }

    public function index(Request $request)
    {
        $rowsPerPage = 10;

        if ($request->has('rowsPerPage')) {
            $rowsPerPage = $request->input('rowsPerPage');
        }

        $quotations = Quotation::orderBy('id', 'DESC');

        if ($request->has('filter')) {
            $filter = $request->input('filter');

            $quotations = $quotations->where(function ($query) use ($filter) {
                $query->where('cite', 'LIKE', "%" . $filter . "%")
                    ->orWhere('customer', 'LIKE', "%" . $filter . "%");
            });
        }

        $quotations = $quotations->paginate($rowsPerPage);

        $response = [
            'quotations' => $quotations,
            'params'     => [
                'total'        => $quotations->total(),
                'current_page' => $quotations->currentPage(),
                'per_page'     => $quotations->perPage(),
            ],
        ];

        return response()->json($response);
    }

    public function show($id)
    {
        try {
            //$quotation = Quotation::with('products')->findOrFail($id);
            $quotation = Quotation::findOrFail($id);
            $quotation->load('products');
            $quotation->load('user');
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => message('MSG011')], 404);
        }
        return response()->json([
            'success' => true,
            'data'    => $quotation,
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $this->quotations->saveQuotation($request);
        if ($data) {
            return response()->json(['message' => message('MSG001')], 200);
        } else {
            return response()->json(['message' => message('MSG010')], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $state = 0;
        if ($request->state) {
            $state = 1;
        }
        try {
            $quotation        = Quotation::find($id);
            $quotation->state = $state;
            $quotation->save();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'message' => message('MSG002'),
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $quotation        = Quotation::find($id);
            $quotation->state = 2;
            $quotation->save();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Se anulo el registro correctamente!',
        ], 200);
    }
}
