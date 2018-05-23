<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        try {
            if ($request->get('image')) {
                $image = $request->get('image');
                $name  = time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                $path  = public_path('img/products/' . $name);
                \Image::make($request->get('image'))->save($path);

                $img = Image::create([
                    'image'      => $name,
                    'product_id' => $request->id,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'data'    => $img,
            'message' => message('MSG001'),
        ], 201);
    }

    public function destroy($id)
    {
        try {
            $image = Image::find($id);
            $image->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json(['message' => message('MSG003')], 200);
    }
}
