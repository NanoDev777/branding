<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $rowsPerPage = 10;

        if ($request->has('rowsPerPage')) {
            $rowsPerPage = $request->input('rowsPerPage');
        }

        $users = User::orderBy('id', 'DESC');

        if ($request->has('filter')) {
            $filter = $request->input('filter');

            $users = $users->where(function ($query) use ($filter) {
                $query->where('name', 'LIKE', "%" . $filter . "%")
                    ->orWhere('email', 'LIKE', "%" . $filter . "%");
            });
        }

        $users = $users->paginate($rowsPerPage);

        $response = [
            'users'  => $users,
            'params' => [
                'total'        => $users->total(),
                'current_page' => $users->currentPage(),
                'per_page'     => $users->perPage(),
            ],
        ];

        return response()->json($response);
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => message('MSG011')], 404);
        }
        return response()->json([
            'success' => true,
            'data'    => $user,
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $inputs = $request->all();
            User::create($inputs);
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'message' => message('MSG001'),
        ], 201);
    }

    public function authenticated()
    {
        $auth = auth()->user();
        $acl  = [];
        foreach ($auth->profile->actions as $action) {
            if (strpos($action->method, '|') !== false) {
                $pipe  = explode('|', $action->method);
                $acl[] = $pipe[0];
                $acl[] = $pipe[1];
            } else {
                $acl[] = $action->method;
            }
        }
        $user = [
            'id'         => $auth->id,
            'name'       => $auth->name,
            'profile_id' => $auth->profile_id,
            //'email' => $auth->email,
            'acl'        => $acl,
        ];
        return response()->json($user);
    }

    public function logout(Request $request)
    {
        if (Cache::has('actions_' . $request->id)) {
            Cache::forget('actions_' . $request->id);
        }
        return response()->json([
            'status'  => 'positive',
            'message' => message('MSG007'),
        ]);
    }
}
