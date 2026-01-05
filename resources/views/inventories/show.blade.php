<x-app-layout>
    {{-- Store session data in PHP variable to avoid Blade syntax in JavaScript --}}
    @php
        $successMessage = session('success');
    @endphp

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    <div class="unit-table-container max-h-[400px] overflow-y-auto border rounded-lg">
                <table class="unit-table">
                    <thead class="sticky top-0 bg-gray-50 z-10">
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
                                @if ($unit->photo)
                                <img
                                    src="{{ asset($unit->photo) }}"
                                    class="w-full h-40 object-cover rounded-lg shadow border"
                                    alt="Foto Unit"
                                    onerror="this.onerror=null;this.src='https:placehold.co/600x400?text=Foto+Tidak+Ditemukan';">
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

        @if ($successMessage)
    <script>
        /* eslint-disable */
        window.addEventListener('load', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ $successMessage }}',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b82f6',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });
        /* eslint-enable */
    </script>
    @endif
</x-app-layout>
