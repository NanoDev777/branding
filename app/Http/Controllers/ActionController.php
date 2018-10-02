<?php

namespace App\Http\Controllers;

use App\Action;

class ActionController extends Controller
{
    public function index()
    {
        $actions = Action::orderBy('order')->pluck('name', 'id');
        return response()->json([
            'list' => $actions,
        ], 200);
    }
}
