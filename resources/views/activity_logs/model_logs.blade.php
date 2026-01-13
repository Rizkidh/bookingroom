<x-app-layout>
    <x-breadcrumbs :items="[
        'Activity Logs' => route('activity-logs.index'),
        class_basename($modelType) . ' ' . $modelId => '#'
    ]" />

    <div class="flex-1 flex flex-col min-h-0 overflow-hidden">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4 border border-gray-100 flex-shrink-0 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Aktivitas: {{ class_basename($modelType) }} #{{ $modelId }}
                </h2>
                <p class="text-[10px] text-gray-400 font-mono mt-1">{{ $modelType }}</p>
            </div>
            <a href="{{ route('activity-logs.index') }}" class="btn-secondary py-2 px-4 text-xs font-semibold rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <!-- Content Area -->
        <div class="flex-1 flex flex-col bg-white rounded-xl shadow-sm border border-gray-100 min-h-0 overflow-hidden">
            <div class="flex-1 overflow-y-auto min-h-0 bg-gray-50/50">
                <!-- Desktop Table -->
                <div class="hidden md:block">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">Waktu</th>
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200 text-center">Aksi</th>
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">User</th>
                                <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">Catatan</th>
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
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center text-[10px] font-bold text-blue-600 border border-blue-100">
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
                                            <span class="text-xs text-gray-600 leading-relaxed">{{ Str::limit($log->note, 150) }}</span>
                                        @else
                                            <span class="text-[10px] text-gray-300 italic">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-20 text-center text-gray-400 italic">Belum ada aktivitas terekam untuk entitas ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden p-4 space-y-3">
                    @forelse ($logs as $log)
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                            <div class="flex justify-between mb-2">
                                <span class="text-[10px] text-gray-400 font-mono">{{ $log->created_at->format('d/m/y H:i') }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $actionClass }}">
                                    {{ $log->action }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-700 mb-3">{{ $log->note ?? 'Perubahan data.' }}</p>
                            <div class="text-[9px] text-gray-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                {{ $log->user_name ?? 'System' }} ({{ $log->user_role ?? 'System' }})
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-400 text-xs italic">Belum ada aktivitas.</div>
                    @endforelse
                </div>
            </div>

            @if ($logs->count() > 0)
                <div class="p-4 border-t border-gray-100 bg-white flex-shrink-0">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
