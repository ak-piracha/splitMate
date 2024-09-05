<?php

namespace App\Http\Controllers;

use App\Models\Appliance;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplianceController extends Controller
{
    public function addAppliance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'cost_per_hour' => 'required|numeric|min:0',
            'place_id' => 'required|exists:places,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $appliance = Appliance::create([
            'name' => $request->name,
            'cost_per_hour' => $request->cost_per_hour,
            'place_id' => $request->place_id,
        ]);

        return response()->json(['message' => 'Appliance added successfully', 'appliance' => $appliance], 201);
    }

    public function viewAppliances($place_id)
    {
        $appliances = Appliance::where('place_id', $place_id)->get();
        return response()->json($appliances);
    }

    public function editAppliance(Request $request, $id)
    {
        $appliance = Appliance::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'cost_per_hour' => 'sometimes|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $appliance->update($request->only(['name', 'cost_per_hour']));

        return response()->json(['message' => 'Appliance updated successfully', 'appliance' => $appliance], 200);
    }

    public function deleteAppliance($id)
    {
        $appliance = Appliance::findOrFail($id);
        $appliance->delete();

        return response()->json(['message' => 'Appliance deleted successfully'], 200);
    }
}
