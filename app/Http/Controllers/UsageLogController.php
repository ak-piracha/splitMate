<?php

namespace App\Http\Controllers;

use App\Models\UsageLog;
use App\Models\Appliance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsageLogController extends Controller
{
    public function startUsage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appliance_id' => 'required|exists:appliances,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $log = UsageLog::create([
            'tenant_id' => Auth::id(),
            'appliance_id' => $request->appliance_id,
            'start_time' => now(),
        ]);

        return response()->json(['message' => 'Usage started', 'log' => $log], 201);
    }

    public function stopUsage($id)
    {
        $log = UsageLog::findOrFail($id);

        if ($log->tenant_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $log->end_time = now();
        $log->duration = $log->end_time->diffInHours($log->start_time);
        $log->cost = $log->duration * $log->appliance->cost_per_hour;
        $log->save();

        return response()->json(['message' => 'Usage stopped', 'log' => $log], 200);
    }

    public function viewLogs($appliance_id)
    {
        $logs = UsageLog::where('appliance_id', $appliance_id)
                        ->where('tenant_id', Auth::id())
                        ->get();

        return response()->json($logs);
    }

    public function viewBill()
    {

        $logs = UsageLog::where('tenant_id', Auth::id())->get(); $total_cost = $logs->sum('cost');

            return response()->json([
                'logs' => $logs,
                'total_cost' => $total_cost,
            ]);
    }
}
