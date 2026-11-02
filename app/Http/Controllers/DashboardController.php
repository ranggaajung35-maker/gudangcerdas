<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'suppliers' => Supplier::count(),
            'low_stock' => Product::whereColumn('stock', '<=', 'min_stock')->count(),
            'inventory_value' => (float) Product::selectRaw('COALESCE(SUM(stock * purchase_price), 0) AS total')->value('total'),
            'stock_in_month' => (int) StockMovement::where('type', 'in')->whereMonth('movement_date', now()->month)->whereYear('movement_date', now()->year)->sum('quantity'),
            'stock_out_month' => (int) StockMovement::where('type', 'out')->whereMonth('movement_date', now()->month)->whereYear('movement_date', now()->year)->sum('quantity'),
            'users' => User::count(),
        ];

        $recentMovements = StockMovement::with(['product:id,sku,name,unit', 'user:id,name'])
            ->latest('movement_date')->latest('id')->limit(7)->get();

        $lowStockProducts = Product::with('category:id,name')
            ->whereColumn('stock', '<=', 'min_stock')->orderBy('stock')->limit(6)->get();

        $monthlyMovement = StockMovement::query()
            ->selectRaw('MONTH(movement_date) AS month_number, type, SUM(quantity) AS total')
            ->whereYear('movement_date', now()->year)
            ->groupBy(DB::raw('MONTH(movement_date)'), 'type')->get();

        $chart = collect(range(1, 12))->map(function (int $month) use ($monthlyMovement) {
            return [
                'label' => now()->setMonth($month)->translatedFormat('M'),
                'in' => (int) $monthlyMovement->where('month_number', $month)->where('type', 'in')->sum('total'),
                'out' => (int) $monthlyMovement->where('month_number', $month)->where('type', 'out')->sum('total'),
            ];
        });

        return view('dashboard', compact('stats', 'recentMovements', 'lowStockProducts', 'chart'));
    }
}
