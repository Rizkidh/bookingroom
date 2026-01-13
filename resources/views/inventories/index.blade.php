<x-app-layout>
    @php
        $successMessage = session('success');
    @endphp

    <div class="hidden lg:block">
        <x-breadcrumbs :items="['Inventaris' => route('inventories.index')]" />
    </div>

    <div class="flex-1 flex flex-col min-h-0 overflow-hidden">
        <div class="flex-1 flex flex-col bg-white rounded-xl shadow-sm border border-gray-100 min-h-0 overflow-hidden">
            <!-- Header (Fixed) -->
            <div class="p-3 sm:p-4 border-b border-gray-100 flex-shrink-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <h2 class="text-sm sm:text-base font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Manajemen Inventaris
                </h2>
                <div class="flex gap-2 w-full sm:w-auto">
                    <a href="{{ route('inventories.export') }}" class="btn-success flex-1 sm:flex-none px-3 sm:px-4 py-2 text-[10px] sm:text-xs flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="sm:inline">Export</span>
                    </a>
                    <a href="{{ route('inventories.create') }}" class="btn-primary flex-1 sm:flex-none px-3 sm:px-4 py-2 text-[10px] sm:text-xs flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah
                    </a>
                </div>
            </div>

            <!-- Content (Scrollable) -->
            <div class="flex-1 overflow-y-auto relative min-h-0 bg-gray-50/50">
                @if ($inventoryItems->isEmpty())
                    <div class="h-full flex flex-col items-center justify-center p-12 text-center text-gray-400">
                        <div class="w-16 h-16 bg-white border border-gray-200 rounded-full flex items-center justify-center mb-4 shadow-sm">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900">Belum Ada Barang</h3>
                        <p class="text-xs text-gray-500 mt-1 max-w-xs mx-auto">Mulailah dengan menambahkan kategori barang baru ke dalam sistem inventaris Anda.</p>
                    </div>
                @else
                    <!-- Desktop Table -->
                    <div class="hidden md:block">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">Nama Barang / Kategori</th>
                                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200 text-center w-32">Total</th>
                                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200 text-center w-32">Tersedia</th>
                                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200 text-center w-32">Rusak</th>
                                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200 text-right w-48">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($inventoryItems as $item)
                                <tr class="hover:bg-blue-50/40 transition-colors group">
                                    <td class="px-5 py-3">
                                        <a href="{{ route('inventories.show', $item->id) }}" class="font-bold text-gray-800 text-sm hover:text-blue-600 transition block">
                                            {{ $item->name }}
                                        </a>
                                        @if($item->note)
                                        <div class="text-[10px] text-gray-400 mt-0.5 truncate max-w-xs">{{ $item->note }}</div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="inline-flex items-center justify-center min-w-[32px] h-6 px-2 text-xs font-bold text-blue-700 bg-blue-100 rounded-full">
                                            {{ $item->total_stock }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="inline-flex items-center justify-center min-w-[32px] h-6 px-2 text-xs font-bold text-green-700 bg-green-100 rounded-full">
                                            {{ $item->available_stock }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        @if($item->damaged_stock > 0)
                                            <span class="inline-flex items-center justify-center min-w-[32px] h-6 px-2 text-xs font-bold text-red-700 bg-red-100 rounded-full">
                                                {{ $item->damaged_stock }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-300 font-bold">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-100 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('inventories.edit', $item->id) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            @can('delete', $item)
                                            <form action="{{ route('inventories.destroy', $item->id) }}" method="POST" class="swal-delete inline-block" data-item-name="{{ $item->name }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-lg transition-colors" title="Hapus Permanen">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                            @endcan
                                            <a href="{{ route('inventories.show', $item->id) }}" class="p-1.5 text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards (Premium Design) -->
                    <div class="md:hidden p-4 space-y-4">
                        @foreach ($inventoryItems as $item)
                        <a href="{{ route('inventories.show', $item->id) }}" 
                           class="block bg-white rounded-2xl border border-gray-100 shadow-md overflow-hidden active:scale-[0.98] transition-transform duration-150">
                            <div class="p-5">
                                <!-- Item Header -->
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $item->name }}</h3>
                                        @if($item->note)
                                        <p class="text-sm text-gray-400 mt-1 line-clamp-2">{{ $item->note }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1 bg-gray-100 rounded-full p-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                </div>

                                <!-- Stats Row -->
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-full text-xs font-bold">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        {{ $item->total_stock }} Total
                                    </span>
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 text-green-700 rounded-full text-xs font-bold">
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                        {{ $item->available_stock }} Tersedia
                                    </span>
                                    @if($item->damaged_stock > 0)
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-50 text-red-600 rounded-full text-xs font-bold">
                                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                        {{ $item->damaged_stock }} Rusak
                                    </span>
                                    @endif
                                </div>
                            </div>

                            @can('delete', $item)
                            <!-- Delete Action Footer -->
                            <div class="flex items-center border-t border-gray-100 bg-gray-50/70">
                                <form action="{{ route('inventories.destroy', $item->id) }}" method="POST" class="swal-delete w-full" data-item-name="{{ $item->name }}" onclick="event.stopPropagation();">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 text-sm font-semibold text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Hapus Barang
                                    </button>
                                </form>
                            </div>
                            @endcan
                        </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Footer (Pagination) -->
            @if ($inventoryItems->isNotEmpty())
                <div class="p-4 border-t border-gray-100 bg-white flex-shrink-0">
                    {{ $inventoryItems->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
