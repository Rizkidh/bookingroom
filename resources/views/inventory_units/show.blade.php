<x-app-layout>
    <x-breadcrumbs :items="['Inventaris' => route('inventories.index'), $inventory->name => route('inventories.show', $inventory->id), 'Detail Unit' => route('inventories.units.show', [$inventory->id, $unit->id])]" />
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                    Detail Unit: {{ $unit->serial_number ?? 'Unit #' . $unit->id }}
                </h2>

                <p class="text-gray-500 mb-6">
                    Bagian dari item:
                    <a href="{{ route('inventories.show', $inventory->id) }}"
                        class="text-indigo-600 hover:text-indigo-800 font-medium">
                        {{ $inventory->name }}
                    </a>
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <p class="text-lg font-semibold text-gray-700 mb-2 border-b">Foto Unit</p>

                        @if ($unit->photo)
                        <img
                            src="{{ asset($unit->photo) }}"
                            class="w-full h-auto rounded-lg shadow border mb-4"
                            alt="Foto Unit">
                        @else
                        <div class="bg-gray-100 p-8 rounded text-center text-gray-500 mb-4">
                            Tidak ada foto unit
                        </div>
                        @endif

                        <div class="mt-4 text-center">
                            <p class="text-sm font-semibold text-gray-700 mb-2">QR Code</p>

                            @if($unit->qr_code)
                            <img
                                src="{{ asset($unit->qr_code) }}"
                                alt="QR Code"
                                class="mx-auto w-40 h-40 border rounded shadow">

                            <a href="{{ asset($unit->qr_code) }}"
                                download
                                class="inline-block mt-2 text-sm text-indigo-600 hover:underline">
                                Download QR Code
                            </a>
                            @else
                            <p class="text-sm text-gray-500">QR Code belum tersedia</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <p class="text-lg font-semibold text-gray-700 mb-2 border-b">Informasi Unit</p>

                        <div class="mb-4">
                            <p class="text-sm text-gray-500">Nomor Serial</p>
                            <p class="font-bold">{{ $unit->serial_number ?? 'N/A' }}</p>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-500">Status</p>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @class([
                                    'bg-green-100 text-green-800' => $unit->condition_status === 'available',
                                    'bg-yellow-100 text-yellow-800' => $unit->condition_status === 'in_use',
                                    'bg-red-100 text-red-800' => in_array($unit->condition_status, ['damaged','maintenance']),
                                ])">
                                {{ ucfirst(str_replace('_', ' ', $unit->condition_status)) }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-500">Pemegang</p>
                            <p>{{ $unit->current_holder ?? 'Gudang' }}</p>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm text-gray-500">Dibuat</p>
                            <p>{{ $unit->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 mt-8 pt-4 border-t">

                    @can('update', $unit)
                    <a href="{{ route('inventories.units.edit', [$inventory->id, $unit->id]) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit Unit
                    </a>
                    @endcan

                    @can('delete', $unit)
                    <form action="{{ route('inventories.units.destroy', [$inventory->id, $unit->id]) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus unit ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Hapus Unit
                        </button>
                    </form>
                    @endcan

                    <a href="{{ route('inventories.show', $inventory->id) }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>