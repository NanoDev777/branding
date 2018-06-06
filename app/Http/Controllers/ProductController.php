<?php

namespace App\Http\Controllers;

use App\Product;
use App\Quotation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $rowsPerPage = 10;

        if ($request->has('rowsPerPage')) {
            $rowsPerPage = $request->input('rowsPerPage');
        }

        $products = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id', 'products.code', 'products.name', 'categories.name as category')
            ->orderBy('id', ' DESC');

        if ($request->has('filter')) {
            $filter = $request->input('filter');

            $products = $products->where(function ($query) use ($filter) {
                $query->where('products.name', 'LIKE', "%" . $filter . "%")
                    ->orWhere('products.code', 'LIKE', "%" . $filter . "%")
                    ->orWhere('categories.name', 'LIKE', "%" . $filter . "%");
            });
        }

        $products = $products->paginate($rowsPerPage);

        $response = [
            'products' => $products,
            'params'   => [
                'total'        => $products->total(),
                'current_page' => $products->currentPage(),
                'per_page'     => $products->perPage(),
            ],
        ];

        return response()->json($response);
    }

    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->load('colors');
            $product->load('images');
            $product->load('packing');
            $product->load('amounts');
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => message('MSG011')], 404);
        }
        return response()->json([
            'success' => true,
            'data'    => $product,
        ], 200);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $code = Product::where('code', $request->code)->get()->first();
            if ($code) {
                return response()->json([
                    'success' => false,
                    'message' => message('MSG013'),
                ], 500);
            }
            $product = Product::create([
                'code'        => $request->code,
                'name'        => $request->name,
                'description' => $request->description,
                'width'       => $request->width,
                'height'      => $request->height,
                'thickness'   => $request->thickness,
                'weight'      => $request->weight,
                'category_id' => $request->category_id,
            ]);

            $data = array();
            foreach ($request->items as $key => $value) {
                if (empty($value['size']['value'])) {
                    $format = 'S/T';
                } else {
                    $format = implode(',', $value['size']['value']);
                }
                $data[$value['id']] = ['size' => $format];
            }

            $product->colors()->attach($data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'id'      => $product->id,
            'message' => message('MSG001'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $product              = Product::find($id);
            $product->code        = $request->code;
            $product->name        = $request->name;
            $product->description = $request->description;
            $product->width       = $request->width;
            $product->height      = $request->height;
            $product->thickness   = $request->thickness;
            $product->weight      = $request->weight;
            $product->category_id = $request->category_id;
            $product->save();

            $data = array();
            foreach ($request->items as $key => $value) {
                if (empty($value['size']['value'])) {
                    $format = 'S/T';
                } else {
                    $format = implode(',', $value['size']['value']);
                }
                $data[$value['id']] = ['size' => $format];
            }
            $product->colors()->sync($data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'id'      => $product->id,
            'message' => message('MSG002'),
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            $product->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json(['message' => message('MSG003')], 200);
    }

    public function filterProducts($category)
    {
        $products = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id', 'products.code', 'products.name', 'categories.name as category')
            ->where('products.category_id', '=', $category)
            ->get();
        if ($products) {
            return response()->json(['error' => 'No se encontraron resultados'], 404);
        } else {
            return response()->json(['data' => $products], 200);
        }
    }

    public function searchProduct($code)
    {
        $product = Product::leftJoin('images', 'products.id', '=', 'images.product_id')
            ->select('products.id', 'products.code', 'products.name', 'images.image as avatar')
            ->where('code', 'like', '%' . $code . '%')
            ->get();
        return response()->json(['data' => $product], 200);
    }

    //cambiar array push
    public function selectProduct(Request $request)
    {
        $data  = json_decode($request->json_data, true);
        $array = array();
        foreach ($data as $key => $value) {
            $products = Product::where('id', $value)
                ->select('id', 'code', 'name', DB::raw('0 as quantity'), DB::raw('"" as url'))
                ->first();
            $products->load('images');
            $array[] = $products;
        }
        return response()->json(['data' => $array], 200);
    }

    public function maxProducts()
    {
        $products = Product::join('product_quotation', 'products.id', '=', 'product_quotation.product_id')
            ->select('products.code', 'products.name', DB::raw('COUNT(*) as cotizaciones'), DB::raw('SUM(product_quotation.quantity) as total'))
            ->groupBy('products.id')
            ->orderBy('cotizaciones', 'DESC')->take(5)
            ->get();
        return response()->json([
            'success' => true,
            'data'    => $products,
        ], 200);
    }

    public function totalProductsQuotations()
    {
        $products   = Product::count();
        $quotations = Quotation::count();
        $data       = ['products' => $products, 'quotations' => $quotations];
        return response()->json([
            'success' => true,
            'data'    => $data,
        ], 200);
    }
}
