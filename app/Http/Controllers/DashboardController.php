<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryUnit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalUnits = InventoryUnit::count();
        $availableUnits = InventoryUnit::where('condition_status', 'available')->count();
        $damagedUnits = InventoryUnit::where('condition_status', 'damaged')->count();
        $inUseUnits = InventoryUnit::where('condition_status', 'in_use')->count();
        $maintenanceUnits = InventoryUnit::where('condition_status', 'maintenance')->count();

        $query = InventoryUnit::with('item');

        if ($request->filled('condition')) {
            $query->where('condition_status', $request->condition);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('item', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $units = $query->orderBy('updated_at', 'desc')->paginate(15)->withQueryString();

        $data = [
            'total' => $totalUnits,
            'available' => $availableUnits,
            'damaged' => $damagedUnits,
            'in_use' => $inUseUnits,
            'maintenance' => $maintenanceUnits,
            'units' => $units,
            'totalItemTypes' => InventoryItem::count(),
        ];

        return view('dashboard', $data);
    }
}
