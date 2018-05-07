<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Price;

class PriceController extends Controller
{
    public function index(){
      $prices = Price::orderBy('id', 'DESC')->get();
      return response()->json(['data' => $prices], 200);
    }

    public function store(Request $request){
    	try {
    	    $price = Price::create([
    		    'quantity' => $request->quantity,
    		    'logo' => $request->logo,
    		    'utility' => $request->utility
    	    ]);
    	    return response()->json(['data' => $price], 200);
    	} catch (\Exception $e) {
            return response()->json(['msg' => 'No es posible registrar los datos'], 500);
        }
    }

    public function update(Request $request, $id){
    	try {
    		$price = Price::find($id);
    	    $price->quantity = $request->quantity;
    	    $price->logo = $request->logo;
    	    $price->utility = $request->utility;
    	    $price->save();
        } catch (\Exception $e) {
            return response()->json(['msg' => 'No es posible actualizar los datos'], 500);
        }
        return response()->json(['data' => $price], 200);
    }

    public function destroy($id){
    	try {
    		$price = Price::find($id);
    	    $price->delete();
        } catch (\Exception $e) {
            return response()->json(['msg' => 'No es posible eliminar'], 500);
        }
        return response()->json(['response' => 'Eliminada correctamente!'], 200);
    }
}
