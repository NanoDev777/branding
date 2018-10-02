<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        try {
            if ($request->file('file')) {
                $name = str_random(10) . '.' . $request->file('file')->getClientOriginalExtension();
                $path = public_path('img/products/' . $name);
                \Image::make($request->file('file'))->save($path);

                $img = Image::create([
                    'image'      => $name,
                    'product_id' => $request->product,
                ]);

                return response()->json([
                    'success' => true,
                    'data'    => $img,
                    'message' => message('MSG001'),
                ], 201);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $image   = Image::find($id);
            $deleted = Storage::disk('public')->delete('img/products/' . $image->image);
            if ($deleted) {
                $image->delete();
                return response()->json([
                    'success' => true,
                    'message' => message('MSG003'),
                ], 200);
            }
            throw new \Exception();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
    }
}
