<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryUnit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $statusCounts = InventoryUnit::selectRaw('condition_status, count(*) as count')
            ->groupBy('condition_status')
            ->pluck('count', 'condition_status')
            ->toArray();

        $totalUnits = array_sum($statusCounts);
        $availableUnits = $statusCounts['available'] ?? 0;
        $damagedUnits = $statusCounts['damaged'] ?? 0;
        $inUseUnits = $statusCounts['in_use'] ?? 0;
        $maintenanceUnits = $statusCounts['maintenance'] ?? 0;

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

        $units = $query->orderBy('updated_at', 'desc')->paginate(10)->withQueryString();

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
