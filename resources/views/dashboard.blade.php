<x-app-layout>
    <x-breadcrumbs :items="[]" />

    <div class="dashboard-header mb-4 sm:mb-6">
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-2">Dashboard Inventaris</h1>
        <p class="text-sm sm:text-base text-gray-600 hidden sm:block">Monitoring dan pengelolaan inventaris barang Anda secara real-time</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
        <a href="{{ route('dashboard') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-3 sm:p-5 text-white cursor-pointer hover:shadow-xl transition-shadow transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-xs sm:text-sm font-medium opacity-90">Total Barang</div>
                    <div class="text-xl sm:text-2xl lg:text-3xl font-bold mt-1 sm:mt-2">{{ $total }}</div>
                    <div class="text-xs opacity-75 mt-1 hidden sm:block">Total unit dalam inventaris</div>
                </div>
                <svg class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 opacity-20 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042L5.85 15.5h5.3L9.05 9.5h8.5a1 1 0 00.957-1.387l-1.476-5A1 1 0 0015.582 3H9.5a1 1 0 00-.986.836l-.564-2.26A1 1 0 006 1H3z"/>
                </svg>
            </div>
        </a>

        <a href="?condition=available" class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-3 sm:p-5 text-white cursor-pointer hover:shadow-xl transition-shadow transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-xs sm:text-sm font-medium opacity-90">Tersedia</div>
                    <div class="text-xl sm:text-2xl lg:text-3xl font-bold mt-1 sm:mt-2">{{ $available }}</div>
                    <div class="text-xs opacity-75 mt-1 hidden sm:block">Unit siap digunakan</div>
                </div>
                <svg class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 opacity-20 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
        </a>

        <a href="?condition=damaged" class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-3 sm:p-5 text-white cursor-pointer hover:shadow-xl transition-shadow transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-xs sm:text-sm font-medium opacity-90">Rusak</div>
                    <div class="text-xl sm:text-2xl lg:text-3xl font-bold mt-1 sm:mt-2">{{ $damaged }}</div>
                    <div class="text-xs opacity-75 mt-1 hidden sm:block">Unit perlu perbaikan</div>
                </div>
                <svg class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 opacity-20 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 2.476M14.89 2.11a6 6 0 01.9 8.12l2.736-2.735a1 1 0 10-1.414-1.414L14.475 8.586a6 6 0 01-6.365-2.475" clip-rule="evenodd"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('inventories.index') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-3 sm:p-5 text-white cursor-pointer hover:shadow-xl transition-shadow transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-xs sm:text-sm font-medium opacity-90">Jenis Barang</div>
                    <div class="text-xl sm:text-2xl lg:text-3xl font-bold mt-1 sm:mt-2">{{ $totalItemTypes }}</div>
                    <div class="text-xs opacity-75 mt-1 hidden sm:block">Tipe barang terdaftar</div>
                </div>
                <svg class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 opacity-20 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zm0 8a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z"/>
                </svg>
            </div>
        </a>
    </div>

    <div class="inventory-section">
        <div class="section-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0 p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-white">
                Detail Unit Inventaris
                @if (request('condition') === 'available')
                    <span class="text-green-200 text-base sm:text-lg">(Unit Tersedia)</span>
                @elseif (request('condition') === 'damaged')
                    <span class="text-red-200 text-base sm:text-lg">(Unit Rusak)</span>
                @elseif (request('condition') === 'in_use')
                    <span class="text-blue-200 text-base sm:text-lg">(Sedang Digunakan)</span>
                @elseif (request('condition') === 'maintenance')
                    <span class="text-yellow-200 text-base sm:text-lg">(Dalam Perawatan)</span>
                @endif
            </h2>
            <a href="{{ route('inventories.index') }}" class="add-btn text-sm sm:text-base px-4 py-2">
                Kelola Jenis Barang
            </a>
        </div>

        <div class="p-4 bg-gray-50 border-b">
            <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                @if(request('condition'))
                    <input type="hidden" name="condition" value="{{ request('condition') }}">
                @endif
                <div class="flex-1 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID Unit atau Serial Number..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Cari</button>
                    @if(request('search') || request('condition'))
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        @if ($units->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">Belum ada data</div>
                <p class="empty-state-text">Belum ada unit inventaris. Mulai dengan menambahkan jenis barang baru.</p>
                <a href="{{ route('inventories.create') }}" class="empty-state-btn">Tambah Jenis Barang Pertama</a>
            </div>
        @else
            <div class="hidden md:block table-wrapper max-h-[400px] overflow-y-auto border rounded-lg">
                <table class="inventory-table">
                        <thead class="sticky top-0 bg-gray-50 z-10">
                            <tr>
                                <th>ID Unit</th>
                                <th>Jenis Barang</th>
                                <th>Serial Number</th>
                                <th>Status</th>
                                <th>Pemegang</th>
                                <th>Terakhir Update</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $unit)
                            <tr>
                                <td class="item-name font-mono font-semibold">{{ $unit->id }}</td>
                                <td>
                                    @if($unit->item)
                                        <a href="{{ route('inventories.show', $unit->item->id) }}" class="text-blue-600 hover:underline">
                                            {{ $unit->item->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($unit->serial_number)
                                        <span class="font-mono">{{ $unit->serial_number }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($unit->condition_status === 'available')
                                        <span class="stock-badge badge-available">Tersedia</span>
                                    @elseif($unit->condition_status === 'in_use')
                                        <span class="stock-badge" style="background-color: #3b82f6; color: white;">Digunakan</span>
                                    @elseif($unit->condition_status === 'maintenance')
                                        <span class="stock-badge" style="background-color: #eab308; color: white;">Perawatan</span>
                                    @elseif($unit->condition_status === 'damaged')
                                        <span class="stock-badge badge-damaged">Rusak</span>
                                    @else
                                        <span class="stock-badge">{{ $unit->condition_status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($unit->current_holder)
                                        <span>{{ $unit->current_holder }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td><span class="updated-time">{{ $unit->updated_at->format('Y-m-d H:i') }}</span></td>
                                <td style="text-align: center;">
                                    @if($unit->item)
                                        <a href="{{ route('inventories.units.show', [$unit->item->id, $unit->id]) }}" title="Lihat Detail Unit" class="action-link">
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            <div class="md:hidden space-y-4 p-4">
                @foreach ($units as $unit)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="font-mono font-bold text-lg text-blue-600">{{ $unit->id }}</div>
                            @if($unit->item)
                                <a href="{{ route('inventories.show', $unit->item->id) }}" class="text-blue-600 hover:underline font-semibold">
                                    {{ $unit->item->name }}
                                </a>
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </div>
                        @if($unit->condition_status === 'available')
                            <span class="stock-badge badge-available text-xs">Tersedia</span>
                        @elseif($unit->condition_status === 'in_use')
                            <span class="stock-badge text-xs" style="background-color: #3b82f6; color: white;">Digunakan</span>
                        @elseif($unit->condition_status === 'maintenance')
                            <span class="stock-badge text-xs" style="background-color: #eab308; color: white;">Perawatan</span>
                        @elseif($unit->condition_status === 'damaged')
                            <span class="stock-badge badge-damaged text-xs">Rusak</span>
                        @else
                            <span class="stock-badge text-xs">{{ $unit->condition_status }}</span>
                        @endif
                    </div>

                    <div class="space-y-2 text-sm">
                        @if($unit->serial_number)
                        <div class="flex items-center">
                            <span class="text-gray-500 w-24">Serial:</span>
                            <span class="font-mono">{{ $unit->serial_number }}</span>
                        </div>
                        @endif

                        @if($unit->current_holder)
                        <div class="flex items-center">
                            <span class="text-gray-500 w-24">Pemegang:</span>
                            <span>{{ $unit->current_holder }}</span>
                        </div>
                        @endif

                        <div class="flex items-center">
                            <span class="text-gray-500 w-24">Update:</span>
                            <span class="text-gray-600">{{ $unit->updated_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>

                    @if($unit->item)
                    <div class="mt-4 pt-3 border-t">
                        <a href="{{ route('inventories.units.show', [$unit->item->id, $unit->id]) }}" class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                            Lihat Detail
                        </a>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="p-4 border-t bg-gray-50">
                {{ $units->links() }}
            </div>
            @endif
    </div>
</x-app-layout>
