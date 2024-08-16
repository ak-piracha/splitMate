<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function viewAllUsers()
    {
        $users = User::with('tenants', 'principalTenant')->get();
        return response()->json($users);
    }

    public function updateUserRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string|in:admin,principal_tenant,tenant',
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return response()->json(['message' => 'User role updated successfully', 'user' => $user], 200);
    }

    public function editUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->only(['name', 'email', 'phone', 'profile_picture']));

        return response()->json(['message' => 'User details updated successfully', 'user' => $user], 200);
    }

    public function deactivateUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deactivated successfully'], 200);
    }
}
