<div class="space-y-6">
    <!-- Header -->
    <div class="border-b pb-4">
        <div class="flex justify-between items-start mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Detail Activity Log</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">X</button>
        </div>
    </div>

    <!-- Info Dasar -->
    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase">Action</p>
            <p class="text-lg font-bold">
                @if ($activityLog->action === 'CREATE')
                    <span class="text-green-600">CREATE</span>
                @elseif ($activityLog->action === 'UPDATE')
                    <span class="text-blue-600">UPDATE</span>
                @elseif ($activityLog->action === 'DELETE')
                    <span class="text-red-600">DELETE</span>
                @endif
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase">Waktu</p>
            <p class="text-lg font-bold text-gray-900">
                {{ $activityLog->created_at->format('d/m/Y H:i:s') }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase">Model Type</p>
            <p class="text-sm font-mono text-gray-900">{{ class_basename($activityLog->model_type) }}</p>
        </div>

        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase">Model ID</p>
            <p class="text-sm font-mono text-gray-900">{{ $activityLog->model_id }}</p>
        </div>
    </div>

    <!-- User Info -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">User Information</p>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-semibold text-gray-700">User:</span>
                <span class="text-gray-900">{{ $activityLog->user_name ?? 'System' }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Role:</span>
                <span class="text-gray-900">{{ $activityLog->user_role ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">IP Address:</span>
                <span class="text-gray-900 font-mono">{{ $activityLog->ip_address ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">User Agent:</span>
                <span class="text-gray-900 text-xs">{{ Str::limit($activityLog->user_agent, 40) ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <!-- Catatan User -->
    @if ($activityLog->note)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Catatan</p>
            <p class="text-gray-900 whitespace-pre-wrap">{{ $activityLog->note }}</p>
        </div>
    @endif

    <!-- Old vs New Values -->
    @if ($activityLog->action === 'UPDATE' && ($activityLog->old_values || $activityLog->new_values))
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900">Perubahan Data</h3>

            @php
                $oldValues = $activityLog->old_values ?? [];
                $newValues = $activityLog->new_values ?? [];
                $changedKeys = array_keys(array_merge($oldValues, $newValues));
            @endphp

            @foreach ($changedKeys as $key)
                <div class="border rounded-lg overflow-hidden">
                    <div class="bg-gray-100 px-4 py-2 font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                    <div class="grid grid-cols-2 gap-4 p-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 mb-2">SEBELUM</p>
                            <div class="bg-red-50 border-l-4 border-red-300 p-3 rounded font-mono text-sm text-gray-900">
                                {{ $oldValues[$key] ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 mb-2">SESUDAH</p>
                            <div class="bg-green-50 border-l-4 border-green-300 p-3 rounded font-mono text-sm text-gray-900">
                                {{ $newValues[$key] ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Raw JSON Data -->
    @if ($activityLog->old_values || $activityLog->new_values)
        <details class="border rounded-lg p-4">
            <summary class="cursor-pointer font-semibold text-gray-900 hover:text-blue-600">Raw JSON Data</summary>
            <div class="mt-4 space-y-4">
                @if ($activityLog->old_values)
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">Old Values:</p>
                        <pre class="bg-gray-900 text-green-400 p-3 rounded overflow-auto text-xs">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                @endif

                @if ($activityLog->new_values)
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">New Values:</p>
                        <pre class="bg-gray-900 text-green-400 p-3 rounded overflow-auto text-xs">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                @endif
            </div>
        </details>
    @endif

    <!-- Description -->
    @if ($activityLog->description)
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Deskripsi</p>
            <p class="text-gray-900">{{ $activityLog->description }}</p>
        </div>
    @endif

    <!-- Close Button -->
    <div class="flex justify-end pt-4 border-t">
        <button onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-900 rounded-lg hover:bg-gray-300 transition">
            Tutup
        </button>
    </div>
</div>
