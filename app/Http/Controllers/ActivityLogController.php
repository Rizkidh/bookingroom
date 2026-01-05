<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ActivityLogController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the activity logs
     */
    public function index(Request $request)
    {
        // Authorization: Only Admin/Supervisor can view activity logs
        $this->authorize('viewAny', ActivityLog::class);

        $query = ActivityLog::query();

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->input('model_type'));
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        // Search in description and note
        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        // Get logs with pagination
        $logs = $query->latest('created_at')->paginate(20);

        return view('activity_logs.index', compact('logs'));
    }

    /**
     * Display the specified activity log detail
     */
    public function show(ActivityLog $activityLog)
    {
        $this->authorize('view', $activityLog);

        return view('activity_logs.show', compact('activityLog'));
    }

    /**
     * Get activity logs for specific model
     */
    public function getModelLogs($modelType, $modelId)
    {
        $this->authorize('viewAny', ActivityLog::class);

        $logs = ActivityLog::byModel($modelType, $modelId)
            ->latest('created_at')
            ->paginate(20);

        return view('activity_logs.model_logs', compact('logs', 'modelType', 'modelId'));
    }

    /**
     * Export activity logs to CSV
     */
    public function export(Request $request)
    {
        $this->authorize('viewAny', ActivityLog::class);

        $query = ActivityLog::query();

        // Apply filters
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->input('model_type'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        $logs = $query->latest('created_at')->get();

        // Generate CSV
        $filename = 'activity-logs-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = ['ID', 'Waktu', 'Action', 'Model', 'Model ID', 'User', 'Role', 'Catatan', 'IP Address'];
        $callback = function () use ($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns, ';');

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->action,
                    class_basename($log->model_type),
                    $log->model_id,
                    $log->user_name,
                    $log->user_role,
                    $log->note,
                    $log->ip_address,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
