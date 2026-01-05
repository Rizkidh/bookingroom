<x-app-layout>
    <x-breadcrumbs :items="[]" />

    <div class="flex-1 flex flex-col min-h-0">
        <div class="dashboard-header mb-4 flex-shrink-0">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Dashboard Inventaris</h1>
            <p class="text-xs sm:text-sm text-gray-600 hidden sm:block">Monitoring dan pengelolaan inventaris barang Anda secara real-time</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4 flex-shrink-0">
            <a href="{{ route('dashboard') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow p-3 sm:p-4 text-white cursor-pointer hover:shadow-lg transition-all transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-[10px] sm:text-xs font-medium opacity-90 uppercase tracking-wider">Total Barang</div>
                        <div class="text-lg sm:text-2xl font-bold mt-1">{{ $total }}</div>
                    </div>
                </div>
            </a>

            <a href="?condition=available" class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-3 sm:p-4 text-white cursor-pointer hover:shadow-lg transition-all transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-[10px] sm:text-xs font-medium opacity-90 uppercase tracking-wider">Tersedia</div>
                        <div class="text-lg sm:text-2xl font-bold mt-1">{{ $available }}</div>
                    </div>
                </div>
            </a>

            <a href="?condition=damaged" class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow p-3 sm:p-4 text-white cursor-pointer hover:shadow-lg transition-all transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-[10px] sm:text-xs font-medium opacity-90 uppercase tracking-wider">Rusak</div>
                        <div class="text-lg sm:text-2xl font-bold mt-1">{{ $damaged }}</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('inventories.index') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow p-3 sm:p-4 text-white cursor-pointer hover:shadow-lg transition-all transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-[10px] sm:text-xs font-medium opacity-90 uppercase tracking-wider">Jenis Barang</div>
                        <div class="text-lg sm:text-2xl font-bold mt-1">{{ $totalItemTypes }}</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="pro-table-wrapper flex-1 min-h-0 flex flex-col">
            <div class="section-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 p-4 flex-shrink-0">
                <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Detail Unit Inventaris
                    @if (request('condition'))
                        <span class="text-blue-100 font-normal text-xs px-2 py-0.5 bg-white/20 rounded-full ml-1">
                            {{ ucfirst(str_replace('_', ' ', request('condition'))) }}
                        </span>
                    @endif
                </h2>
                <a href="{{ route('inventories.index') }}" class="add-btn text-xs px-3 py-1.5">
                    Kelola Jenis
                </a>
            </div>

            <div class="p-4 bg-white border-b border-gray-100 flex-shrink-0">
                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col md:flex-row gap-2">
                    @if(request('condition'))
                        <input type="hidden" name="condition" value="{{ request('condition') }}">
                    @endif
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari ID Unit atau Serial..." 
                               class="w-full pl-9 pr-3 py-2 text-sm border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-all">
                            Cari
                        </button>
                        @if(request('search') || request('condition'))
                            <a href="{{ route('dashboard') }}" class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-all">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="pro-table-desktop flex-1 min-h-0">
                @if ($units->isEmpty())
                    <div class="h-full flex flex-col items-center justify-center p-8 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-500 rounded-full mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Belum ada data unit</h3>
                        <p class="text-xs text-gray-500 mt-1">Coba sesuaikan filter atau pencarian Anda.</p>
                    </div>
                @else
                    <table class="pro-table">
                        <thead>
                            <tr>
                                <th>ID Unit</th>
                                <th>Jenis Barang</th>
                                <th>Serial Number</th>
                                <th>Status</th>
                                <th>Holder</th>
                                <th>Update</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $unit)
                            <tr>
                                <td class="font-mono font-bold text-blue-600">{{ $unit->id }}</td>
                                <td>
                                    @if($unit->item)
                                        <a href="{{ route('inventories.show', $unit->item->id) }}" class="font-semibold text-gray-900 hover:text-blue-600">
                                            {{ $unit->item->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-400 italic">No Item</span>
                                    @endif
                                </td>
                                <td><span class="font-mono text-gray-600">{{ $unit->serial_number ?: '-' }}</span></td>
                                <td>
                                    @php
                                        $statusClass = match($unit->condition_status) {
                                            'available' => 'status-available',
                                            'in_use' => 'badge-primary',
                                            'maintenance' => 'status-maintenance',
                                            'damaged' => 'status-damaged',
                                            default => 'badge-secondary'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }} text-[10px]">
                                        {{ ucfirst(str_replace('_', ' ', $unit->condition_status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 bg-gray-100 rounded-full flex items-center justify-center text-[9px] font-bold text-gray-500">
                                            {{ strtoupper(substr($unit->current_holder ?: 'G', 0, 1)) }}
                                        </div>
                                        <span class="text-xs {{ $unit->current_holder ? 'text-gray-900 font-medium' : 'text-gray-400 italic' }}">
                                            {{ $unit->current_holder ?: 'Gudang' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-gray-500 text-[10px]">{{ $unit->updated_at->format('d/m/y H:i') }}</td>
                                <td class="text-center">
                                    @if($unit->item)
                                        <a href="{{ route('inventories.units.show', [$unit->item->id, $unit->id]) }}" 
                                           class="pro-btn-action bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-2 py-1">
                                            <span class="text-[10px]">Detail</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Mobile View (Keep scrollable if needed, but fixed in viewport) -->
            <div class="pro-card-view p-4 overflow-y-auto flex-1 min-h-0">
                @foreach ($units as $unit)
                <div class="pro-card mb-3 last:mb-0 border-none shadow-sm ring-1 ring-gray-200">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">ID UNIT</div>
                            <div class="font-mono font-bold text-base text-blue-600">{{ $unit->id }}</div>
                        </div>
                        <span class="status-badge {{ $statusClass ?? 'badge-secondary' }} text-[9px] px-1.5 py-0.5">
                            {{ ucfirst(str_replace('_', ' ', $unit->condition_status)) }}
                        </span>
                    </div>
                    <div class="flex flex-col mb-3">
                        <span class="text-[9px] font-bold text-gray-400 uppercase">Jenis Barang</span>
                        <span class="text-sm font-bold text-gray-900 truncate">{{ $unit->item ? $unit->item->name : 'N/A' }}</span>
                    </div>
                    @if($unit->item)
                    <a href="{{ route('inventories.units.show', [$unit->item->id, $unit->id]) }}" 
                       class="block w-full text-center py-2 bg-blue-600 text-white rounded-lg text-xs font-bold transition-all">
                        Lihat Detail
                    </a>
                    @endif
                </div>
                @endforeach
            </div>

            @if ($units->isNotEmpty())
            <div class="p-3 border-t bg-gray-50 flex-shrink-0">
                {{ $units->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
