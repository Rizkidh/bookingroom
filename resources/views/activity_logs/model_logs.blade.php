<div class="space-y-4">
    <h2 class="text-2xl font-bold text-gray-900">Activity Logs for {{ class_basename($modelType) }} #{{ $modelId }}</h2>
    
    @if($logs->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($logs as $log)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->action === 'CREATE')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">CREATE</span>
                                @elseif($log->action === 'UPDATE')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">UPDATE</span>
                                @elseif($log->action === 'DELETE')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">DELETE</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->user_name ?? 'System' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $log->description }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    @else
        <p class="text-gray-500">No activity logs found for this model.</p>
    @endif
</div>

