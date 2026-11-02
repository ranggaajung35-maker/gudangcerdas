<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);
        $from = $validated['from'] ?? now()->startOfMonth()->toDateString();
        $to = $validated['to'] ?? now()->toDateString();
        $query = StockMovement::with(['product:id,sku,name,unit', 'user:id,name'])
            ->whereDate('movement_date', '>=', $from)->whereDate('movement_date', '<=', $to);
        $movements = (clone $query)->latest('movement_date')->paginate(15)->withQueryString();
        $summary = [
            'in' => (int) (clone $query)->where('type', 'in')->sum('quantity'),
            'out' => (int) (clone $query)->where('type', 'out')->sum('quantity'),
            'transactions' => (clone $query)->count(),
        ];
        $lowStock = Product::with('category:id,name')->whereColumn('stock', '<=', 'min_stock')->orderBy('stock')->get();
        return view('reports.index', compact('movements', 'summary', 'lowStock', 'from', 'to'));
    }

    public function export(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);
        $from = $validated['from'] ?? now()->startOfMonth()->toDateString();
        $to = $validated['to'] ?? now()->toDateString();

        return response()->streamDownload(function () use ($from, $to) {
            $output = fopen('php://output', 'w');
            // BOM membantu Excel membaca UTF-8 dengan benar.
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['Tanggal', 'SKU', 'Produk', 'Jenis', 'Kuantitas', 'Satuan', 'Referensi', 'Petugas', 'Catatan'], ';', '"', '');
            $safeCell = static function ($value) {
                if (is_string($value) && preg_match('/^[\x00-\x20]*[=+@-]/u', $value)) {
                    return "'".$value;
                }
                return $value;
            };
            StockMovement::with(['product:id,sku,name,unit', 'user:id,name'])
                ->whereDate('movement_date', '>=', $from)->whereDate('movement_date', '<=', $to)
                ->orderBy('movement_date')->chunk(300, function ($rows) use ($output, $safeCell) {
                    foreach ($rows as $row) {
                        $cells = [$row->movement_date->format('Y-m-d H:i'), $row->product->sku, $row->product->name, $row->type === 'in' ? 'Masuk' : 'Keluar', $row->quantity, $row->product->unit, $row->reference, $row->user->name, $row->notes];
                        fputcsv($output, array_map($safeCell, $cells), ';', '"', '');
                    }
                });
            fclose($output);
        }, 'laporan-stok-'.$from.'-sampai-'.$to.'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
