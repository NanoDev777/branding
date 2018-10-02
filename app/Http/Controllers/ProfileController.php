<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $rowsPerPage = 10;

        if ($request->has('rowsPerPage')) {
            $rowsPerPage = $request->input('rowsPerPage');
        }

        $profiles = Profile::orderBy('id', 'DESC');

        if ($request->has('filter')) {
            $filter = $request->input('filter');

            $profiles = $profiles->where(function ($query) use ($filter) {
                $query->where('description', 'LIKE', "%" . $filter . "%");
            });
        }

        $profiles = $profiles->paginate($rowsPerPage);

        $response = [
            'profiles' => $profiles,
            'params'   => [
                'total'        => $profiles->total(),
                'current_page' => $profiles->currentPage(),
                'per_page'     => $profiles->perPage(),
            ],
        ];

        return response()->json($response);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $input   = $request->all();
            $profile = Profile::create($input);
            $this->syncActions($profile, $request->input('action_list'));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'status'  => 'positive',
            'message' => message('MSG001'),
        ], 201);
    }

    public function show($id)
    {
        $profile = Profile::with('actions')->find($id);
            return response()->json([
                'success' => true,
                'profile' => $profile,
            ]);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $profile = Profile::find($id);
            $profile->update($request->all());
            $this->syncActions($profile, $request->input('action_list'));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'status'  => 'positive',
            'message' => message('MSG002'),
        ], 201);
    }

    public function destroy($id)
    {
        try {
            $profile = Profile::find($id);
            $profile->users()->update(['active' => 0]);
            $profile->delete();

            return response()->json([
                'success' => true,
                'status'  => 'positive',
                'message' => message('MSG003'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status'  => 'negative',
                'message' => message('MSG005'),
            ]);
        }
    }

    function list() {
        try {
            $profiles = Profile::orderBy('id', 'DESC')->select('id', 'description')->get();
        } catch (\Exception $e) {
            return response()->json(['message' => message('MSG010')], 500);
        }
        return response()->json([
            'success' => true,
            'list'    => $profiles,
        ], 200);
    }

    private function syncActions(Profile $profile, array $actions)
    {
        $profile->actions()->sync($actions);
    }
}
