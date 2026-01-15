<x-app-layout>
    <div class="hidden lg:block">
        <x-breadcrumbs :items="[]" />
    </div>

    <div class="flex-1 flex flex-col min-h-0 overflow-hidden">
        <!-- Stats Grid (Fixed Top) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 mb-4 flex-shrink-0">
            <a href="{{ route('dashboard') }}" class="stat-card total">
                <div class="stat-label">Total Barang</div>
                <div class="stat-value">{{ $total }}</div>
            </a>

            <a href="?condition=available" class="stat-card available">
                <div class="stat-label">Tersedia</div>
                <div class="stat-value">{{ $available }}</div>
            </a>

            <a href="?condition=damaged" class="stat-card damaged">
                <div class="stat-label">Rusak</div>
                <div class="stat-value">{{ $damaged }}</div>
            </a>

            <a href="{{ route('inventories.index') }}" class="stat-card types">
                <div class="stat-label">Jenis Barang</div>
                <div class="stat-value">{{ $totalItemTypes }}</div>
            </a>
        </div>

        <!-- Status Chart Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-4 flex-shrink-0">
            <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                </svg>
                Perbandingan Status Unit Inventaris
            </h3>
            
            <div class="flex flex-col lg:flex-row items-center gap-6">
                <!-- Donut Chart -->
                <div class="relative w-48 h-48 flex-shrink-0">
                    @php
                        $chartTotal = $available + $in_use + $maintenance + $damaged;
                        $availablePercent = $chartTotal > 0 ? ($available / $chartTotal) * 100 : 0;
                        $inUsePercent = $chartTotal > 0 ? ($in_use / $chartTotal) * 100 : 0;
                        $maintenancePercent = $chartTotal > 0 ? ($maintenance / $chartTotal) * 100 : 0;
                        $damagedPercent = $chartTotal > 0 ? ($damaged / $chartTotal) * 100 : 0;
                        
                        // Calculate cumulative percentages for conic-gradient
                        $availableEnd = $availablePercent;
                        $inUseEnd = $availableEnd + $inUsePercent;
                        $maintenanceEnd = $inUseEnd + $maintenancePercent;
                    @endphp
                    
                    @if($chartTotal > 0)
                    <div class="w-full h-full rounded-full relative" 
                         style="background: conic-gradient(
                            #10b981 0% {{ $availableEnd }}%,
                            #3b82f6 {{ $availableEnd }}% {{ $inUseEnd }}%,
                            #f59e0b {{ $inUseEnd }}% {{ $maintenanceEnd }}%,
                            #ef4444 {{ $maintenanceEnd }}% 100%
                         );">
                        <!-- Inner circle for donut effect -->
                        <div class="absolute inset-4 bg-white rounded-full flex items-center justify-center shadow-inner">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-800">{{ $chartTotal }}</div>
                                <div class="text-[10px] text-gray-500 font-medium uppercase tracking-wide">Total Unit</div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="w-full h-full rounded-full bg-gray-200 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-xl font-bold text-gray-400">0</div>
                            <div class="text-[10px] text-gray-400">Tidak ada data</div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Legend -->
                <div class="flex-1 grid grid-cols-2 lg:grid-cols-2 gap-3 w-full">
                    <!-- Available -->
                    <a href="?condition=available" class="flex items-center gap-3 p-3 rounded-lg bg-green-50 hover:bg-green-100 border border-green-100 transition-all group">
                        <div class="w-4 h-4 rounded-full bg-green-500 flex-shrink-0 ring-4 ring-green-100 group-hover:ring-green-200 transition-all"></div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-green-700">Tersedia (Available)</div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-lg font-bold text-green-600">{{ $available }}</span>
                                <span class="text-[10px] text-green-500">({{ number_format($availablePercent, 1) }}%)</span>
                            </div>
                        </div>
                    </a>
                    
                    <!-- In Use -->
                    <a href="?condition=in_use" class="flex items-center gap-3 p-3 rounded-lg bg-blue-50 hover:bg-blue-100 border border-blue-100 transition-all group">
                        <div class="w-4 h-4 rounded-full bg-blue-500 flex-shrink-0 ring-4 ring-blue-100 group-hover:ring-blue-200 transition-all"></div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-blue-700">Digunakan (In Use)</div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-lg font-bold text-blue-600">{{ $in_use }}</span>
                                <span class="text-[10px] text-blue-500">({{ number_format($inUsePercent, 1) }}%)</span>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Maintenance -->
                    <a href="?condition=maintenance" class="flex items-center gap-3 p-3 rounded-lg bg-yellow-50 hover:bg-yellow-100 border border-yellow-100 transition-all group">
                        <div class="w-4 h-4 rounded-full bg-yellow-500 flex-shrink-0 ring-4 ring-yellow-100 group-hover:ring-yellow-200 transition-all"></div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-yellow-700">Perbaikan (Maintenance)</div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-lg font-bold text-yellow-600">{{ $maintenance }}</span>
                                <span class="text-[10px] text-yellow-500">({{ number_format($maintenancePercent, 1) }}%)</span>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Damaged -->
                    <a href="?condition=damaged" class="flex items-center gap-3 p-3 rounded-lg bg-red-50 hover:bg-red-100 border border-red-100 transition-all group">
                        <div class="w-4 h-4 rounded-full bg-red-500 flex-shrink-0 ring-4 ring-red-100 group-hover:ring-red-200 transition-all"></div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-red-700">Rusak (Damaged)</div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-lg font-bold text-red-600">{{ $damaged }}</span>
                                <span class="text-[10px] text-red-500">({{ number_format($damagedPercent, 1) }}%)</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>


        <!-- Main Content Area (Table Wrapper) -->
        <div class="flex-1 flex flex-col bg-white rounded-xl shadow-sm border border-gray-100 min-h-0 overflow-hidden">
            <!-- Table Header & Controls (Fixed) -->
            <div class="p-4 border-b border-gray-100 flex-shrink-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        Detail Unit Inventaris
                        @if (request('condition'))
                            <span class="text-blue-600 bg-blue-50 font-mono text-xs px-2 py-0.5 rounded-full ml-1 border border-blue-100">
                                {{ ucfirst(str_replace('_', ' ', request('condition'))) }}
                            </span>
                        @endif
                    </h2>
                </div>

                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-row gap-2 w-full sm:w-auto">
                    @if(request('condition'))
                        <input type="hidden" name="condition" value="{{ request('condition') }}">
                    @endif
                    <div class="flex-1 sm:w-64 relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari ID Unit, Serial, atau Barang..." 
                               class="w-full pl-3 pr-10 py-2 text-xs border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-medium">
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                    @if(request('search') || request('condition'))
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg text-xs font-bold hover:bg-red-200 transition-all flex items-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto min-h-0 relative bg-gray-50/50">
                <div class="hidden md:block">
                    @if ($units->isEmpty())
                        <div class="h-64 flex flex-col items-center justify-center text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900">Tidak ada data ditemukan</h3>
                            <p class="text-xs text-gray-500 mt-1">Coba kata kunci lain atau reset filter.</p>
                        </div>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">ID Unit</th>
                                    <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">Barang & Kategori</th>
                                    <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">Serial Number</th>
                                    <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">Status</th>
                                    <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">Pemegang</th>
                                    <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($units as $unit)
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-4 py-3 font-mono font-bold text-blue-600 text-[11px]">{{ $unit->id }}</td>
                                    <td class="px-4 py-3">
                                        @if($unit->item)
                                            <div class="flex flex-col">
                                                <a href="{{ route('inventories.show', $unit->item->id) }}" class="text-xs font-bold text-gray-900 hover:text-blue-600">
                                                    {{ $unit->item->name }}
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Item dihapus</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $unit->serial_number ?: '-' }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusClass = match($unit->condition_status) {
                                                'available' => 'bg-green-100 text-green-700 border-green-200',
                                                'in_use' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'maintenance' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                'damaged' => 'bg-red-100 text-red-700 border-red-200',
                                                default => 'bg-gray-100 text-gray-600 border-gray-200'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border {{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $unit->condition_status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-5 h-5 bg-gray-100 rounded-full flex items-center justify-center text-[9px] font-bold text-gray-500">
                                                {{ strtoupper(substr($unit->current_holder ?: 'G', 0, 1)) }}
                                            </div>
                                            <span class="text-xs font-medium text-gray-700">{{ $unit->current_holder ?: 'Gudang' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($unit->item)
                                            <a href="{{ route('inventories.units.show', [$unit->item->id, $unit->id]) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                <!-- Mobile View -->
                <div class="md:hidden p-4 space-y-3">
                     @forelse ($units as $unit)
                        <div class="bg-white p-3 rounded-lg border border-gray-100 shadow-sm flex flex-col gap-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase">ID Unit</div>
                                    <div class="font-mono font-bold text-blue-600">{{ $unit->id }}</div>
                                </div>
                                @php
                                    $statusClassMobile = match($unit->condition_status) {
                                        'available' => 'bg-green-50 text-green-700 border-green-100',
                                        'in_use' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'maintenance' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                        'damaged' => 'bg-red-50 text-red-700 border-red-100',
                                        default => 'bg-gray-50 text-gray-600 border-gray-100'
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded text-[10px] font-bold border {{ $statusClassMobile }}">
                                    {{ ucfirst(str_replace('_', ' ', $unit->condition_status)) }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between border-t border-gray-50 pt-2">
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-gray-400 font-bold">Barang</span>
                                    <span class="text-xs font-bold text-gray-800">{{ $unit->item ? $unit->item->name : 'N/A' }}</span>
                                </div>
                                @if($unit->item)
                                    <a href="{{ route('inventories.units.show', [$unit->item->id, $unit->id]) }}" class="px-3 py-1 bg-gray-100 text-gray-600 rounded text-[10px] font-bold">Detail</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-500 text-xs">Tidak ada data unit.</div>
                    @endforelse
                </div>
            </div>

            <!-- Footer / Pagination (Fixed Bottom) -->
            @if ($units->isNotEmpty())
            <div class="p-4 border-t border-gray-100 bg-white flex-shrink-0">
                {{ $units->onEachSide(1)->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
