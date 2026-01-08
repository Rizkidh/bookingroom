<x-app-layout>
    <x-breadcrumbs :items="['Inventaris' => route('inventories.index'), $inventory->name => route('inventories.show', $inventory->id)]" />
    @php
        $successMessage = session('success');
    @endphp

    <div class="flex-1 flex flex-col min-h-0 overflow-hidden">
        <!-- Main Content Area with 2 Columns for Info & List -->
        <div class="flex-1 flex flex-col md:flex-row gap-4 min-h-0 overflow-hidden">
            
            <!-- Left Panel: Inventory Details (Fixed width on desktop, flexible on mobile) -->
            <div class="w-full md:w-80 flex flex-col gap-4 overflow-y-auto flex-shrink-0">
                <!-- Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col gap-4">
                    <div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nama Barang</div>
                        <h1 class="text-xl font-bold text-gray-900 leading-tight">{{ $inventory->name }}</h1>
                    </div>
                    
                    @if($inventory->note)
                    <div class="p-3 bg-gray-50 rounded-lg text-xs text-gray-600 leading-relaxed border border-gray-100">
                        {{ $inventory->note }}
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-gray-50">
                        <div class="text-center p-2 rounded-lg bg-blue-50 border border-blue-100">
                            <div class="text-[9px] font-bold text-blue-500 uppercase">Total Unit</div>
                            <div class="text-lg font-bold text-blue-700">{{ $units->total() }}</div>
                        </div>
                        <div class="text-center p-2 rounded-lg bg-green-50 border border-green-100">
                            <div class="text-[9px] font-bold text-green-500 uppercase">Siap Pakai</div>
                            <div class="text-lg font-bold text-green-700">{{ $inventory->available_stock }}</div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex flex-col gap-2">
                        <a href="{{ route('inventories.edit', $inventory->id) }}" class="btn-primary w-full justify-center py-2 text-xs">
                            Edit Informasi Barang
                        </a>
                        @can('delete', $inventory)
                        <form action="{{ route('inventories.destroy', $inventory->id) }}" method="POST" class="swal-delete w-full" data-item-name="kategori {{ $inventory->name }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition text-xs shadow-sm shadow-red-100">
                                Hapus Barang
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Right Panel: Unit List (Flex grow & Scrollable) -->
            <div class="flex-1 flex flex-col bg-white rounded-xl shadow-sm border border-gray-100 min-h-0 overflow-hidden">
                <!-- Header (Fixed) -->
                <div class="p-4 border-b border-gray-100 flex-shrink-0 flex items-center justify-between">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Daftar Unit
                    </h2>
                    <a href="{{ route('inventories.units.create', $inventory->id) }}" class="btn-success px-4 py-2 text-xs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Unit
                    </a>
                </div>

                <!-- Table Content (Scrollable) -->
                <div class="flex-1 overflow-y-auto relative min-h-0 bg-gray-50/50">
                    @if ($units->isEmpty())
                        <div class="h-full flex flex-col items-center justify-center p-8 text-center text-gray-400">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm">
                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900">Belum ada unit</h3>
                            <p class="text-xs text-gray-500 mt-1">Tambahkan unit pertama untuk barang ini.</p>
                        </div>
                    @else
                        <!-- Desktop Table -->
                        <div class="hidden md:block">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm">
                                    <tr>
                                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">ID Unit System</th>
                                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">Serial Number</th>
                                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200 text-center">Status</th>
                                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">Pemegang</th>
                                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200 text-center">Dokumentasi</th>
                                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200 text-right w-32">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach ($units as $unit)
                                    <tr class="hover:bg-blue-50/30 transition-colors group">
                                        <td class="px-4 py-3 font-mono font-bold text-blue-600 text-xs text-center border-r border-dashed border-gray-100 bg-gray-50/50 w-24">
                                            {{ $unit->id }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="font-mono text-xs font-medium text-gray-700">{{ $unit->serial_number ?: '-' }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
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
                                                <div class="w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center text-[9px] font-bold text-gray-500">
                                                    {{ strtoupper(substr($unit->current_holder ?: 'G', 0, 1)) }}
                                                </div>
                                                <span class="text-xs text-gray-700">{{ $unit->current_holder ?: 'Gudang' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($unit->photo)
                                                <a href="{{ asset($unit->photo) }}" target="_blank" class="text-blue-600 hover:underline text-[10px] font-bold">Lihat Foto</a>
                                            @else
                                                <span class="text-gray-300 text-[10px] italic">No Pic</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="flex items-center justify-end gap-2 opacity-100 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ route('inventories.units.edit', [$inventory->id, $unit->id]) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>
                                                <a href="{{ route('inventories.units.show', [$inventory->id, $unit->id]) }}" class="p-1.5 text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="Detail">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards -->
                        <div class="md:hidden p-4 space-y-3">
                            @foreach ($units as $unit)
                            <div class="bg-white p-3 rounded-lg border border-gray-100 shadow-sm flex gap-3">
                                <div class="w-16 h-16 bg-gray-50 rounded-lg border border-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden">
                                    @if($unit->photo)
                                        <img src="{{ asset($unit->photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <div class="flex justify-between items-start">
                                        <div class="font-mono font-bold text-blue-600 text-xs">#{{ $unit->id }}</div>
                                        @php
                                            $statusClassMobile = match($unit->condition_status) {
                                                'available' => 'bg-green-50 text-green-700 border-green-100',
                                                'in_use' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                'maintenance' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                                'damaged' => 'bg-red-50 text-red-700 border-red-100',
                                                default => 'bg-gray-50 text-gray-600 border-gray-100'
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $statusClassMobile }}">
                                            {{ ucfirst(str_replace('_', ' ', $unit->condition_status)) }}
                                        </span>
                                    </div>
                                    <div class="text-xs font-bold text-gray-800 truncate">{{ $unit->serial_number ?: 'Tanpa Serial' }}</div>
                                    <div class="flex gap-2 mt-1">
                                        <a href="{{ route('inventories.units.edit', [$inventory->id, $unit->id]) }}" class="flex-1 text-center py-1 bg-gray-100 hover:bg-gray-200 rounded text-[10px] font-bold text-gray-600">Edit</a>
                                        <a href="{{ route('inventories.units.show', [$inventory->id, $unit->id]) }}" class="flex-1 text-center py-1 bg-blue-50 hover:bg-blue-100 rounded text-[10px] font-bold text-blue-600">Detail</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Footer (Pagination) -->
                @if ($units->isNotEmpty())
                    <div class="p-4 border-t border-gray-100 bg-white flex-shrink-0">
                        {{ $units->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
