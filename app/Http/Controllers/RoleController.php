<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $role = Role::all();
        //render view with posts
        return new RoleResource(
            true,
            'List Data Role',
            $role
        );
    }
    public function create()
    {
        return view('role.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_role' => 'required',
            'nama_role' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //Fungsi Post ke Database
        $role = Role::create([
            'id_role' => $request->id_role,
            'nama_role' => $request->nama_role,
        ]);
        return new RoleResource(true, 'Data Role Berhasil Ditambahkan!', $role);
    }

    public function edit($id_role)
    {
        $role = Role::findOrFail($id_role);
        return view('role.edit', compact('role'));
    }

    public function show($id_role)
    {
        $role = Role::find($id_role);

        if (!is_null($role)) {
            return response([
                'message' => 'Retrieve Role Success',
                'data' => $role
            ], 200);
        }

        return response([
            'message' => 'Role not found',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id_role)
    {
        $role = Role::find($id_role);
        if (is_null($role)) {
            return response([
                'message' => 'Role Not Found',
                'data' => null
            ], 404);
        }

        $this->validate($request, [
            'id_role' => 'required',
            'nama_role' => 'required',
        ]);

        $role = Role::findOrFail($id_role);

        $role->update([
            'id_role' => $request->id_role,
            'nama_role' => $request->nama_role,
        ]);

        if ($role) {
            return redirect()
                ->route('role.index')
                ->with([
                    'success' => 'Roler has been updated successfully'
                ]);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'error' => 'Some problem has occured, please try again'
                ]);
        }
    }

    public function destroy($id_role)
    {
        $role = Role::findOrFail($id_role);
        $role->delete();

        if ($role) {
            return redirect()
                ->route('role.index')
                ->with([
                    'success' => 'role has been deleted successfully'
                ]);
        } else {
            return redirect()
                ->route('role.index')
                ->with([
                    'error' => 'Some problem has occurred, please try again'
                ]);
        }
    }
}
