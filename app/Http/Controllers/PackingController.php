<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Packing;

class PackingController extends Controller
{
    
    public function store(Request $request){
    	try {
    	    $packing = Packing::create([
    		    'width' => $request->width,
    		    'height' => $request->height,
    		    'thickness' => $request->thickness,
    		    'weight' => $request->weight,
    		    'box' => $request->box,
    		    'product_id' => $request->product
    	    ]);
    	    return response()->json(['data' => $packing], 200);
    	} catch (\Exception $e) {
            return response()->json(['msg' => 'No es posible registrar los datos'], 500);
        }
    }

    public function update(Request $request, $id){
    	try {
    		$packing = Packing::find($id);
    	    $packing->width = $request->width;
    	    $packing->height = $request->height;
    	    $packing->thickness = $request->thickness;
    	    $packing->weight = $request->weight;
    	    $packing->box = $request->box;
    	    $packing->save();
        } catch (\Exception $e) {
            return response()->json(['msg' => 'No es posible actualizar los datos'], 500);
        }
        return response()->json(['data' => $packing], 200);
    }
}
