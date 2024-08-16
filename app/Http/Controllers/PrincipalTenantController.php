<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PrincipalTenantController extends Controller
{
    // Create a new tenant
    public function createTenant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tenant = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'tenant',
            'principal_tenant_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Tenant created successfully', 'tenant' => $tenant], 201);
    }

    // View all tenants assigned to the principal tenant
    public function viewTenants()
    {
        $tenants = Auth::user()->tenants;

        return response()->json($tenants);
    }
}
