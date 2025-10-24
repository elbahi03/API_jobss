<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(){
        return response()->json(Role::all());
    }

    public function getUsers(){
        $users = Role::where('role', 'user')->get();
        return response()->json($users);
    }

    public function getEmps(){
        $emps = Role::where('role', 'emp')->get();
        return response()->json($emps);
    }

    public function getAdmins(){
        $admins = Role::where('role', 'admin')->get();
        return response()->json($admins);
    }

    public function show($id){
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        return response()->json($role);
    }

    public function update(Request $request, $id){
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $request->validate([
            'role' => 'in:user,emp,admin',
            'user_id' => 'exists:users,id'
        ]);
        $role->update($request->only(['role', 'user_id']));
        return response()->json(['message' => 'Role updated successfully', 'data' => $role]);
    }

    public function destroy($id){
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }
}
