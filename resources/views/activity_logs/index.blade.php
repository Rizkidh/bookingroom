<x-app-layout>
    <x-breadcrumbs :items="['Activity Logs' => route('activity-logs.index')]" />

    <div class="flex-1 flex flex-col min-h-0 overflow-hidden">
        <!-- Filter Bar (Fixed) -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4 border border-gray-100 flex-shrink-0">
            <form method="GET" action="{{ route('activity-logs.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] uppercase font-bold text-gray-400">Tipe Model</label>
                        <select name="model_type" class="form-input py-2 px-3 text-xs w-full bg-gray-50 border-gray-100 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Semua Model --</option>
                            <option value="App\Models\InventoryItem" {{ request('model_type') == 'App\Models\InventoryItem' ? 'selected' : '' }}>Inventory Item</option>
                            <option value="App\Models\InventoryUnit" {{ request('model_type') == 'App\Models\InventoryUnit' ? 'selected' : '' }}>Inventory Unit</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] uppercase font-bold text-gray-400">Aksi</label>
                        <select name="action" class="form-input py-2 px-3 text-xs w-full bg-gray-50 border-gray-100 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Semua Aksi --</option>
                            <option value="CREATE" {{ request('action') == 'CREATE' ? 'selected' : '' }}>CREATE</option>
                            <option value="UPDATE" {{ request('action') == 'UPDATE' ? 'selected' : '' }}>UPDATE</option>
                            <option value="DELETE" {{ request('action') == 'DELETE' ? 'selected' : '' }}>DELETE</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] uppercase font-bold text-gray-400">Cari</label>
                        <input type="text" name="search" placeholder="ID, Catatan, User..." value="{{ request('search') }}" class="form-input py-2 px-3 text-xs w-full bg-gray-50 border-gray-100 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="w-full md:w-auto">
                    <button type="submit" class="btn-primary w-full md:w-auto h-[38px] px-6 text-xs flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Content Area -->
        <div class="flex-1 flex flex-col bg-white rounded-xl shadow-sm border border-gray-100 min-h-0 overflow-hidden">
            <div class="section-header p-4 border-b border-gray-100 flex-shrink-0">
                <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Aktivitas & Audit Trail
                </h2>
            </div>
            
            <div class="flex-1 overflow-y-auto min-h-0 bg-gray-50/50">
                <!-- Desktop Table -->
                <div class="hidden md:block">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">Waktu</th>
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200 text-center">Aksi</th>
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">Entitas</th>
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">User</th>
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($logs as $log)
                                <tr class="hover:bg-blue-50/20 transition-colors">
                                    <td class="px-5 py-3">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-gray-700">{{ $log->created_at->format('d M Y') }}</span>
                                            <span class="text-[10px] font-mono text-gray-400">{{ $log->created_at->format('H:i:s') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        @php
                                            $actionClass = match($log->action) {
                                                'CREATE' => 'bg-green-100 text-green-700 border-green-200',
                                                'UPDATE' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'DELETE' => 'bg-red-100 text-red-700 border-red-200',
                                                default => 'bg-gray-100 text-gray-600 border-gray-200'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold border {{ $actionClass }}">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-gray-800">{{ class_basename($log->model_type) }}</span>
                                            <span class="text-[10px] font-mono text-blue-600">ID: {{ $log->model_id }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-[10px] font-bold text-blue-700 border border-blue-200">
                                                {{ strtoupper(substr($log->user_name ?? 'S', 0, 1)) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-semibold text-gray-700">{{ $log->user_name ?? 'System' }}</span>
                                                <span class="text-[9px] text-gray-400">{{ $log->user_role ?? 'Automated' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3">
                                        @if ($log->note)
                                            <div class="text-xs text-gray-600 leading-relaxed max-w-sm">
                                                {{ Str::limit($log->note, 120) }}
                                            </div>
                                        @else
                                            <span class="text-[10px] text-gray-300 italic">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <p class="text-sm font-bold text-gray-500">Tidak ada log aktivitas ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden p-4 space-y-3">
                    @forelse ($logs as $log)
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col gap-3">
                            <div class="flex justify-between items-start">
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-gray-400 font-mono">{{ $log->created_at->format('d/m/y H:i') }}</span>
                                    <span class="text-xs font-bold text-blue-600">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</span>
                                </div>
                                @php
                                    $actionClass = match($log->action) {
                                        'CREATE' => 'bg-green-50 text-green-700 border-green-100',
                                        'UPDATE' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'DELETE' => 'bg-red-50 text-red-700 border-red-100',
                                        default => 'bg-gray-50 text-gray-600 border-gray-100'
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded text-[10px] font-bold border {{ $actionClass }}">
                                    {{ $log->action }}
                                </span>
                            </div>
                            
                            <div class="text-xs text-gray-600 bg-gray-50 p-2 rounded-lg border border-gray-100">
                                {{ $log->note ?? 'Tidak ada catatan.' }}
                            </div>

                            <div class="flex items-center gap-2 pt-2 border-t border-gray-50">
                                <div class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center text-[9px] font-bold text-blue-600">
                                    {{ strtoupper(substr($log->user_name ?? 'S', 0, 1)) }}
                                </div>
                                <span class="text-[10px] font-semibold text-gray-500">{{ $log->user_name ?? 'System' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-400 text-xs">Belum ada aktivitas.</div>
                    @endforelse
                </div>
            </div>

            @if ($logs->count() > 0)
                <div class="p-4 border-t border-gray-100 bg-white flex-shrink-0">
                    {{ $logs->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
