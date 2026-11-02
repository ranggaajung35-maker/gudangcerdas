<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'in:in,out'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);
        $term = trim($filters['q'] ?? '');
        $movements = StockMovement::with(['product:id,sku,name,unit', 'user:id,name'])
            ->when(! empty($filters['type']), fn ($q) => $q->where('type', $filters['type']))
            ->when($term !== '', function ($q) use ($term) {
                $q->where(fn ($sub) => $sub->where('reference', 'like', "%{$term}%")
                    ->orWhereHas('product', fn ($product) => $product->where('name', 'like', "%{$term}%")->orWhere('sku', 'like', "%{$term}%")));
            })
            ->when(! empty($filters['from']), fn ($q) => $q->whereDate('movement_date', '>=', $filters['from']))
            ->when(! empty($filters['to']), fn ($q) => $q->whereDate('movement_date', '<=', $filters['to']))
            ->latest('movement_date')->paginate(15)->withQueryString();
        return view('stock-movements.index', compact('movements'));
    }

    public function create(): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get(['id', 'sku', 'name', 'stock', 'unit']);
        return view('stock-movements.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        DB::transaction(function () use ($data, $request) {
            $product = Product::whereKey($data['product_id'])->lockForUpdate()->firstOrFail();
            $this->applyMovement($product, $data['type'], (int) $data['quantity']);
            StockMovement::create($data + ['user_id' => $request->user()->id]);
        });
        return redirect()->route('stock-movements.index')->with('success', 'Transaksi stok berhasil disimpan dan saldo persediaan diperbarui.');
    }

    public function edit(StockMovement $stockMovement): View
    {
        $products = Product::orderBy('name')->get(['id', 'sku', 'name', 'stock', 'unit']);
        return view('stock-movements.edit', ['movement' => $stockMovement, 'products' => $products]);
    }

    public function update(Request $request, StockMovement $stockMovement): RedirectResponse
    {
        $data = $this->validated($request);
        DB::transaction(function () use ($data, $request, $stockMovement) {
            $movement = StockMovement::whereKey($stockMovement->id)->lockForUpdate()->firstOrFail();
            $productIds = collect([(int) $movement->product_id, (int) $data['product_id']])->unique()->sort()->values();
            // Mengunci produk dalam urutan ID yang sama mengurangi risiko deadlock.
            $lockedProducts = Product::whereIn('id', $productIds)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $oldProduct = $lockedProducts->get((int) $movement->product_id);
            $newProduct = $lockedProducts->get((int) $data['product_id']);

            if (! $oldProduct || ! $newProduct) {
                throw ValidationException::withMessages(['product_id' => 'Produk transaksi tidak ditemukan.']);
            }

            $this->reverseMovement($oldProduct, $movement->type, (int) $movement->quantity);
            $this->applyMovement($newProduct, $data['type'], (int) $data['quantity']);
            $movement->update($data + ['user_id' => $request->user()->id]);
        });
        return redirect()->route('stock-movements.index')->with('success', 'Transaksi diperbarui dan stok dihitung ulang.');
    }

    public function destroy(StockMovement $stockMovement): RedirectResponse
    {
        DB::transaction(function () use ($stockMovement) {
            $movement = StockMovement::whereKey($stockMovement->id)->lockForUpdate()->firstOrFail();
            $product = Product::whereKey($movement->product_id)->lockForUpdate()->firstOrFail();
            $this->reverseMovement($product, $movement->type, (int) $movement->quantity);
            $movement->delete();
        });
        return redirect()->route('stock-movements.index')->with('success', 'Transaksi dihapus dan perubahan stok dibatalkan.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100000000'],
            'reference' => ['nullable', 'string', 'max:60', 'not_regex:/<[^>]*>/'],
            'notes' => ['nullable', 'string', 'max:1000', 'not_regex:/<[^>]*>/'],
            'movement_date' => ['required', 'date', 'before_or_equal:today'],
        ]);
    }

    private function applyMovement(Product $product, string $type, int $quantity): void
    {
        if ($type === 'out' && $product->stock < $quantity) {
            throw ValidationException::withMessages(['quantity' => "Stok {$product->name} tidak mencukupi. Stok saat ini: {$product->stock} {$product->unit}."]);
        }
        $product->stock = $type === 'in' ? $product->stock + $quantity : $product->stock - $quantity;
        $product->save();
    }

    private function reverseMovement(Product $product, string $type, int $quantity): void
    {
        if ($type === 'in' && $product->stock < $quantity) {
            throw ValidationException::withMessages(['movement' => 'Transaksi masuk tidak dapat dibatalkan karena sebagian stok sudah terpakai. Buat transaksi penyesuaian terlebih dahulu.']);
        }
        $product->stock = $type === 'in' ? $product->stock - $quantity : $product->stock + $quantity;
        $product->save();
    }
}
