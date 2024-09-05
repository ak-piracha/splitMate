<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function addPlace(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'unique_id' => 'required|string|max:255|unique:places,unique_id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $place = Place::create([
            'name' => $request->name,
            'location' => $request->location,
            'unique_id' => $request->unique_id,
            'principal_tenant_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Place added successfully', 'place' => $place], 201);
    }

    public function viewPlaces()
    {
        $places = Auth::user()->places;
        return response()->json($places);
    }

    public function editPlace(Request $request, $id)
    {
        $place = Place::findOrFail($id);

        if ($place->principal_tenant_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',
            'unique_id' => 'sometimes|string|max:255|unique:places,unique_id,' . $place->id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $place->update($request->only(['name', 'location', 'unique_id']));

        return response()->json(['message' => 'Place updated successfully', 'place' => $place], 200);
    }

    public function deletePlace($id)
    {
        $place = Place::findOrFail($id);

        if ($place->principal_tenant_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $place->delete();

        return response()->json(['message' => 'Place deleted successfully'], 200);
    }

    public function assignTenantToPlace(Request $request, $place_id)
    {
        $place = Place::findOrFail($place_id);

        if ($place->principal_tenant_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'tenant_email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tenant = User::where('email', $request->tenant_email)->first();

        if ($tenant->role !== 'tenant') {
            return response()->json(['message' => 'User is not a tenant'], 400);
        }

        $place->tenants()->attach($tenant->id);

        return response()->json(['message' => 'Tenant assigned to place successfully']);
    }

    public function viewTenantsInPlace($place_id)
    {
        $place = Place::findOrFail($place_id);

        if ($place->principal_tenant_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tenants = $place->tenants;
        return response()->json($tenants);
    }
}
