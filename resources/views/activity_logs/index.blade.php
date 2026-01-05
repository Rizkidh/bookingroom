<x-app-layout>
    <x-breadcrumbs :items="['Activity Logs' => route('activity-logs.index')]" />

    <div class="flex-1 flex flex-col min-h-0">
        <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-3 flex-shrink-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Activity Log (Audit Trail)</h1>
                <p class="text-xs text-gray-600 mt-1">Riwayat lengkap semua aktivitas dan perubahan data dalam sistem</p>
            </div>
            <a href="{{ route('activity-logs.export', request()->all()) }}" class="flex items-center bg-green-600 text-white px-3 py-1.5 rounded-lg hover:bg-green-700 transition text-xs font-semibold">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 mb-4 border border-gray-100 flex-shrink-0">
            <form method="GET" action="{{ route('activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Tipe Model</label>
                    <select name="model_type" class="w-full px-2 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/20">
                        <option value="">-- Semua --</option>
                        <option value="App\Models\InventoryItem" {{ request('model_type') == 'App\Models\InventoryItem' ? 'selected' : '' }}>Inventory Item</option>
                        <option value="App\Models\InventoryUnit" {{ request('model_type') == 'App\Models\InventoryUnit' ? 'selected' : '' }}>Inventory Unit</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Aksi</label>
                    <select name="action" class="w-full px-2 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/20">
                        <option value="">-- Semua --</option>
                        <option value="CREATE" {{ request('action') == 'CREATE' ? 'selected' : '' }}>CREATE</option>
                        <option value="UPDATE" {{ request('action') == 'UPDATE' ? 'selected' : '' }}>UPDATE</option>
                        <option value="DELETE" {{ request('action') == 'DELETE' ? 'selected' : '' }}>DELETE</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Pencarian</label>
                    <input type="text" name="search" placeholder="Cari catatan/user..." value="{{ request('search') }}" class="w-full px-2 py-1.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-1.5 rounded-lg hover:bg-blue-700 transition text-sm font-semibold">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="pro-table-wrapper flex-1 min-h-0 flex flex-col">
            <div class="p-3 md:p-4 border-b border-gray-100 bg-gray-50/50 flex flex-shrink-0">
                <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Audit Trail Aktivitas
                </h2>
            </div>

            {{-- Desktop Table --}}
            <div class="pro-table-desktop flex-1 min-h-0">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th class="w-40">Waktu</th>
                            <th class="w-24 text-center">Aksi</th>
                            <th class="w-48">Model</th>
                            <th class="w-48">User</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td class="whitespace-nowrap font-medium text-gray-600">
                                    <span class="text-[11px]">{{ $log->created_at->format('d M Y') }}</span>
                                    <span class="block text-[10px] text-gray-400 font-mono">{{ $log->created_at->format('H:i:s') }}</span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $actionBadge = match($log->action) {
                                            'CREATE' => 'badge-create',
                                            'UPDATE' => 'badge-update',
                                            'DELETE' => 'badge-delete',
                                            default => 'badge-system'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $actionBadge }} text-[9px] px-2 py-0.5">{{ $log->action }}</span>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800 text-xs">{{ class_basename($log->model_type) }}</span>
                                        <span class="text-[10px] font-mono text-gray-500">#{{ $log->model_id }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-[10px]">
                                            {{ strtoupper(substr($log->user_name ?? 'S', 0, 1)) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-gray-800 text-xs truncate max-w-[120px]">{{ $log->user_name ?? 'System' }}</span>
                                            <span class="text-[10px] text-gray-500">{{ $log->user_role ?? 'Automated' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="max-w-xs">
                                    @if ($log->note)
                                        <div class="text-[11px] text-gray-700 leading-tight">
                                            {{ Str::limit($log->note, 100) }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic text-[10px]">No notes</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <p class="text-gray-500 text-sm">Tidak ada activity log ditemukan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="pro-card-view p-4 overflow-y-auto flex-1 min-h-0">
                @forelse ($logs as $log)
                    <div class="pro-card mb-3 last:mb-0 border-none shadow-sm ring-1 ring-gray-100">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[9px] text-gray-400 font-mono">{{ $log->created_at->format('d/m/y H:i:s') }}</span>
                            @php
                                $actionBadgeMob = match($log->action) {
                                    'CREATE' => 'badge-create',
                                    'UPDATE' => 'badge-update',
                                    'DELETE' => 'badge-delete',
                                    default => 'badge-system'
                                };
                            @endphp
                            <span class="status-badge {{ $actionBadgeMob }} text-[9px] px-1.5 py-0.5">{{ $log->action }}</span>
                        </div>
                        <div class="text-xs font-bold text-blue-700 mb-1">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</div>
                        <div class="text-[11px] text-gray-600 mb-2">{{ $log->note ?? 'No activity notes.' }}</div>
                        <div class="flex items-center gap-1.5 pt-2 border-t border-gray-50">
                            <div class="w-4 h-4 rounded-full bg-gray-100 flex items-center justify-center text-[8px] font-bold text-gray-400">
                                {{ strtoupper(substr($log->user_name ?? 'S', 0, 1)) }}
                            </div>
                            <span class="text-[10px] text-gray-500 font-medium">{{ $log->user_name ?? 'System' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-xs italic">Belum ada aktivitas</p>
                    </div>
                @endforelse
            </div>

            @if ($logs->count() > 0)
                <div class="p-3 border-t bg-gray-50 flex-shrink-0">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .form-input {
            @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition;
        }
    </style>
</x-app-layout>
