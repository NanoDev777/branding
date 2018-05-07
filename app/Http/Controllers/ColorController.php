<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Color;

class ColorController extends Controller
{
    public function index() {
      $colors = Color::orderBy('id', 'DESC')->get();
      if (is_null($colors)) 
      	return response()->json(['msg' => 'No se encontraron resultados'], 404);
      else
      	return response()->json(['data' => $colors], 200);
    }

    public function store(Request $request) {
      $this->validate($request, [
        'name' => 'required',
      ]);

      $color = Color::create([
        'name' => $request->name
      ]);
      return response()->json(['data' => $color, 'msg' => 'El color se ha registrado correctamente'], 201);
    }
}
