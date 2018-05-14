<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Product;

class ProductController extends Controller
{
    public function index(Request $request){
      $rowsPerPage = 5;
        
      if($request->has('rowsPerPage')){
        $rowsPerPage = $request->input('rowsPerPage');
      }

      $products = Product::join('categories','products.category_id','=','categories.id')
                  ->select('products.id','products.code','products.name','categories.name as category')
                  ->orderBy('id', ' DESC');

      if($request->has('filter')) {
        $filter = $request->input('filter');

        $products = $products->where(function($query) use ($filter) {
          $query->where('products.name', 'LIKE', "%". $filter ."%")
                ->orWhere('products.code', 'LIKE', "%". $filter ."%")
                ->orWhere('categories.name', 'LIKE', "%". $filter ."%");
        });
      }

      $products = $products->paginate($rowsPerPage);

      $response = [
        'products' => $products,
        'params' => [
          'total' => $products->total(),
          'current_page' => $products->currentPage(),
          'per_page' => $products->perPage(),     
        ]
      ];
        
      return response()->json($response);
    }

    public function show($id) {
      try {
        $product = Product::findOrFail($id);
        $product->load('colors');
        $product->load('images');
        $product->load('packing');
      } catch (ModelNotFoundException $e) {
          return response()->json(['error' => 'No se encontro el recurso'], 500);
      }
      return response()->json(['error' => 'No se encontro el recurso'], 500);
    }

    public function store(Request $request){
      DB::beginTransaction();
      try {
        $product = Product::create([
            'code' => $request->code, 
            'name' => $request->name,
            'description' => $request->description,
            'width' => $request->width,
            'height' => $request->height,
            'thickness' => $request->thickness,
            'weight' => $request->weight,
            'price' => $request->price,
            'category_id' => $request->category
        ]);

        $data = array();
        foreach ($request->items as $key => $value) {
          if (empty($value['size']['value'])) {
            $format = 'S/T';
          }else {
            $format = implode(',' , $value['size']['value']);
          } 
          $data[$value['id']] = ['size' => $format];
        }

        /*$format = '';
          if (empty($value['size']['value'])) {
            $format = 'S/T';
          }else {
            $format = implode(',' , $value['size']['value']);
          } */

        $product->colors()->attach($data);

        $p = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id','products.code','products.name','categories.name as category')
            ->where('products.id', $product->id)
            ->first();
        DB::commit();
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['msg' => 'No se pudo registrar el producto'], 500);
      }
      return response()->json(['data' => $p, 'msg' => 'El producto se ha registrado correctamente'], 201);
    }

    public function update(Request $request, $id){
      DB::beginTransaction();
      try {
        $product = Product::find($id);
        $product->code = $request->code; 
        $product->name = $request->name;
        $product->description = $request->description;
        $product->size = $request->size;
        $product->width = $request->width;
        $product->height = $request->height;
        $product->thickness = $request->thickness;
        $product->weight = $request->weight;
        $product->price = $request->price;
        $product->category_id = $request->category;
        $product->save();
        
        $data = array();
        foreach ($request->items as $key => $value) {
          if (empty($value['size']['value'])) {
            $format = 'S/T';
          }else {
            $format = implode(',' , $value['size']['value']);
          }
          $data[$value['id']] = ['size' => $format];
        }
        $product->colors()->sync($data);

        $p = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id','products.code','products.name','categories.name as category')
            ->where('products.id', $id)
            ->first();
        DB::commit();
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['msg' => 'No se pudo actualizar el producto'], 500);
      }
      return response()->json(['data' => $p, 'msg' => 'El producto se ha actualizado correctamente'], 200); 
    }

    public function filterProducts($category){
      $products = Product::join('categories','products.category_id','=','categories.id')
      ->select('products.id','products.code','products.name','categories.name as category')
      ->where('products.category_id', '=' , $category)
      ->get();
      if ($products)
        return response()->json(['error' => 'No se encontraron resultados'], 404);
      else
        return response()->json(['data' => $products], 200);
    }

    public function searchProduct($code) {
      $product = DB::table('products')
                  ->leftJoin('images', 'products.id', '=', 'images.product_id')
                  ->select('products.id', 'products.code', 'products.name', 'images.image as avatar')
                  ->where('code', 'like',  '%' . $code .'%')
                  ->get();
      return response()->json(['data' => $product], 200);
    }

    //cambiar array push
    public function selectProduct(Request $request){
      $data = json_decode($request->json_data, true);
      $array = array();
      foreach ($data as $key => $value){
        $products = Product::where('id', $value)
                    ->select('id', 'code', 'name', DB::raw('0 as quantity'), DB::raw('0 as url'))
                    ->first();
        $products->load('images');
        array_push($array, $products);
      }
      return response()->json(['data' => $array], 200);
    }

    public function destroy($id) {
      if ($id) {
        Product::destroy($id);
        return response()->json(['data' => 'deleted correctly']);
      }
    }
}
