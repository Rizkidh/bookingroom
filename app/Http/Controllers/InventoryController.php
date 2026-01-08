<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InventoryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $inventoryItems = InventoryItem::orderBy('name')->paginate(10);
        return view('inventories.index', compact('inventoryItems'));
    }

    public function create()
    {
        $this->authorize('create', InventoryItem::class);
        return view('inventories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', InventoryItem::class);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:inventory_items,name',
            'note' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        InventoryItem::create([
            'name' => $request->name,
            'note' => $request->note,
            'total_stock' => 0,
            'available_stock' => 0,
            'damaged_stock' => 0,
        ]);

        return redirect()->route('inventories.index')->with('success', 'Item berhasil ditambahkan! Anda dapat menambahkan unit sekarang.');
    }

    public function show(InventoryItem $inventory)
    {
        $units = $inventory->units()->paginate(10);
        $this->authorize('view', $inventory);
        return view('inventories.show', compact('inventory', 'units'));
    }

    public function edit(InventoryItem $inventory)
    {
        $this->authorize('update', $inventory);
        return view('inventories.edit', compact('inventory'));
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $this->authorize('update', $inventory);

        $validator = Validator::make($request->all(), [
            'name' => ['required', Rule::unique('inventory_items')->ignore($inventory->id)],
            'note' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['name', 'note']);
        $inventory->update($data);

        return redirect()->route('inventories.index')->with('success', 'Item berhasil diperbarui');
    }

    public function destroy(InventoryItem $inventory)
    {
        $this->authorize('delete', $inventory);

        // Cek apakah ada unit yang terhubung
        if ($inventory->units()->exists()) {
            return back()->with('error', "Gagal menghapus! Masih ada {$inventory->units()->count()} unit yang terhubung dengan kategori {$inventory->name}. Hapus semua unit terlebih dahulu.");
        }

        if ($inventory->photo) {
            Storage::disk('public')->delete($inventory->photo);
        }
        $inventory->delete();

        return redirect()->route('inventories.index')->with('success', 'Item berhasil dihapus');
    }

    public function export()
    {
        $this->authorize('viewAny', InventoryItem::class);

        $items = InventoryItem::orderBy('name')->get();

        $filename = 'inventory-items-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = ['ID', 'Nama Barang', 'Total Stok', 'Tersedia', 'Rusak', 'Terakhir Update'];
        
        $callback = function () use ($items, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->name,
                    $item->total_stock,
                    $item->available_stock,
                    $item->damaged_stock,
                    $item->updated_at->format('d/m/Y H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
