<x-app-layout>
    <div class="unit-detail-container">
        <div class="unit-detail-card">

            <div class="unit-detail-header">
                <h2 class="unit-detail-title">{{ $inventory->name }}</h2>
            </div>

            <div class="unit-section-header">
                <h3 class="unit-section-title">
                    Unit Detail <span class="unit-count-badge">{{ $units->count() }} Unit</span>
                </h3>
                <a href="{{ route('inventories.units.create', $inventory->id) }}" class="btn-add-unit">
                    + Tambah Unit Satuan
                </a>
            </div>

            <div class="unit-table-container">
                <table class="unit-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nomor Serial</th>
                            <th>Status</th>
                            <th>Pemegang</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($units as $unit)
                        <tr>
                            <td>
                            @if ($unit->photo && file_exists(public_path($unit->photo)))
                                <img 
                                    src="{{ asset($unit->photo) }}"
                                    class="w-full h-auto rounded-lg shadow border"
                                    alt="Foto Unit"
                                >
                            @else
                                <div class="bg-gray-100 p-8 rounded text-center text-gray-500">
                                    Tidak ada foto unit
                                </div>
                            @endif
                            </td>
                            <td><span class="serial-number">{{ $unit->serial_number ?? 'N/A' }}</span></td>
                            <td>
                                <span class="status-badge status-{{ $unit->condition_status }}">
                                    {{ ucfirst(str_replace('_', ' ', $unit->condition_status)) }}
                                </span>
                            </td>
                            <td>
                                <span class="{{ $unit->current_holder ? 'unit-holder' : 'unit-holder-gudang' }}">
                                    {{ $unit->current_holder ?? 'Gudang' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('inventories.units.show', [$inventory->id, $unit->id]) }}" class="btn-action btn-detail">Detail</a>
                                    <a href="{{ route('inventories.units.edit', [$inventory->id, $unit->id]) }}" class="btn-action btn-edit">Edit</a>
                                    @can('delete', $unit)
                                    <form action="{{ route('inventories.units.destroy', [$inventory->id, $unit->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-600 text-white px-4 py-2 rounded">
                                            Hapus Unit
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon"></div>
                                    <p class="empty-state-text">Belum ada unit satuan yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
