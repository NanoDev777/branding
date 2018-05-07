<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function index(){
      $categories = Category::orderBy('id', 'DESC')->get();
      return response()->json(['data' => $categories], 200);
    }

    public function store(Request $request){
    	try {
    	    $category = Category::create([
    		    'name' => $request->name,
    		    'description' => $request->description
    	    ]);
    	    return response()->json(['data' => $category], 200);
    	} catch (\Exception $e) {
            return response()->json(['msg' => 'No es posible registrar la categoría'], 500);
        }
    }

    public function update(Request $request, $id){
    	try {
    		$category = Category::find($id);
    	    $category->name = $request->name;
    	    $category->description = $request->description;
    	    $category->save();
        } catch (\Exception $e) {
            return response()->json(['msg' => 'No es posible actualizar la categoría'], 500);
        }
        return response()->json(['data' => $category], 200);
    }

    public function destroy($id){
    	try {
    		$category = Category::find($id);
    	    $category->delete();
        } catch (\Exception $e) {
            return response()->json(['msg' => 'No es posible eliminar la categoría'], 500);
        }
        return response()->json(['response' => 'Eliminada correctamente!'], 200);
    }

}
