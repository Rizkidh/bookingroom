<x-app-layout>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Activity Log (Audit Trail)</h1>
        <p class="text-gray-600 mt-2">Riwayat lengkap semua aktivitas dan perubahan data dalam sistem</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Model</label>
                    <select name="model_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Model --</option>
                        <option value="App\Models\InventoryItem" {{ request('model_type') == 'App\Models\InventoryItem' ? 'selected' : '' }}>Inventory Item</option>
                        <option value="App\Models\InventoryUnit" {{ request('model_type') == 'App\Models\InventoryUnit' ? 'selected' : '' }}>Inventory Unit</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                    <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Action --</option>
                        <option value="CREATE" {{ request('action') == 'CREATE' ? 'selected' : '' }}>CREATE</option>
                        <option value="UPDATE" {{ request('action') == 'UPDATE' ? 'selected' : '' }}>UPDATE</option>
                        <option value="DELETE" {{ request('action') == 'DELETE' ? 'selected' : '' }}>DELETE</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                    <input type="text" name="search" placeholder="Cari catatan/user..." value="{{ request('search') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Cari
                    </button>
                </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto max-h-[400px] overflow-y-auto">
            <table class="w-full">
            <thead class="sticky top-0 bg-gray-50 border-b-2 border-gray-200 z-10">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Action</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Model</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($log->action === 'CREATE')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">CREATE</span>
                                @elseif ($log->action === 'UPDATE')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">UPDATE</span>
                                @elseif ($log->action === 'DELETE')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">DELETE</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ class_basename($log->model_type) }}</span><br>
                                <span class="text-gray-500 text-xs">ID: {{ $log->model_id }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $log->user_name ?? 'System' }}</div>
                                <div class="text-xs text-gray-500">{{ $log->user_role ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($log->note)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-2 rounded text-gray-800">
                                        {{ Str::limit($log->note, 50) }}
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                Tidak ada activity log yang ditemukan
                            </td>
                        </tr>
                    @endforelse
            </tbody>
            </table>
        </div>
    </div>

    @if ($logs->count() > 0)
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    @endif

    <style>
        .form-input {
            @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition;
        }
    </style>
</x-app-layout>
