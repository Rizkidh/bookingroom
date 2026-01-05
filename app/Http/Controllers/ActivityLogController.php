<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ActivityLogController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', ActivityLog::class);

        $query = ActivityLog::query();

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->input('model_type'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        $logs = $query->latest('created_at')->paginate(20);

        return view('activity_logs.index', compact('logs'));
    }

    public function show(ActivityLog $activityLog)
    {
        $this->authorize('view', $activityLog);

        return view('activity_logs.show', compact('activityLog'));
    }

    public function getModelLogs($modelType, $modelId)
    {
        $this->authorize('viewAny', ActivityLog::class);

        $logs = ActivityLog::byModel($modelType, $modelId)
            ->latest('created_at')
            ->paginate(20);

        return view('activity_logs.model_logs', compact('logs', 'modelType', 'modelId'));
    }

    public function export(Request $request)
    {
        $this->authorize('viewAny', ActivityLog::class);

        $query = ActivityLog::query();

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

        $filename = 'activity-logs-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = ['ID', 'Waktu', 'Action', 'Model', 'Model ID', 'User', 'Role', 'Catatan', 'IP Address'];
        $callback = function () use ($logs, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns);

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
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
