<?php
namespace App\Http\Controllers;
use App\Models\Incident;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    /**
     * Get activity logs for a specific incident
     */

    public function activityLogs(Incident $incident): JsonResponse
    {
        $logs = ActivityLog::where('incident->id', $incident->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'old_status' => $log->old_status,
                    'new_status' => $log->new_status,
                    'comment' => $log->comment,
                    'updated_by' => $log->user ? $log->user->name : 'System',
                    'updated_at' => $log->created_at->toDateTimeString(),
                ];
            });

        return response()->json($logs);
    }

}
