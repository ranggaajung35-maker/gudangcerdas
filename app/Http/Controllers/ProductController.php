<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:150'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'stock' => ['nullable', 'in:low'],
        ]);
        $term = trim($filters['q'] ?? '');
        $products = Product::with(['category:id,name', 'supplier:id,company_name'])
            ->when($term !== '', function ($query) use ($term) {
                $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('sku', 'like', "%{$term}%"));
            })
            ->when(! empty($filters['category_id']), fn ($q) => $q->where('category_id', $filters['category_id']))
            ->when(($filters['stock'] ?? null) === 'low', fn ($q) => $q->whereColumn('stock', '<=', 'min_stock'))
            ->orderBy('name')->paginate(12)->withQueryString();

        $categories = Category::orderBy('name')->get(['id', 'name']);
        return view('products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        return view('products.create', ['product' => new Product(), 'categories' => Category::where('is_active', true)->orderBy('name')->get(), 'suppliers' => Supplier::orderBy('company_name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Product::create($this->validated($request));
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan. Stok awal dimulai dari nol; gunakan transaksi stok untuk menambah persediaan.');
    }

    public function show(Product $product): View
    {
        $product->load(['category', 'supplier', 'stockMovements.user']);
        $movements = $product->stockMovements()->with('user:id,name')->latest('movement_date')->paginate(10);
        return view('products.show', compact('product', 'movements'));
    }

    public function edit(Product $product): View
    {
        return view('products.edit', ['product' => $product, 'categories' => Category::where('is_active', true)->orWhereKey($product->category_id)->orderBy('name')->get(), 'suppliers' => Supplier::orderBy('company_name')->get()]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validated($request, $product));
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->stockMovements()->exists()) {
            return back()->with('error', 'Produk memiliki riwayat transaksi stok sehingga tidak dapat dihapus. Nonaktifkan produk sebagai gantinya.');
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'sku' => ['required', 'string', 'min:3', 'max:40', 'alpha_dash', Rule::unique('products', 'sku')->ignore($product?->id)],
            'name' => ['required', 'string', 'min:2', 'max:150', 'not_regex:/<[^>]*>/'],
            'description' => ['nullable', 'string', 'max:2000', 'not_regex:/<[^>]*>/'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'purchase_price' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
            'selling_price' => ['required', 'numeric', 'gte:purchase_price', 'max:999999999999.99'],
            'min_stock' => ['required', 'integer', 'min:0', 'max:100000000'],
            'unit' => ['required', 'string', 'max:30', 'not_regex:/<[^>]*>/'],
            'location' => ['nullable', 'string', 'max:80', 'not_regex:/<[^>]*>/'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        foreach (['sku', 'name', 'description', 'unit', 'location'] as $field) {
            if (isset($data[$field])) $data[$field] = trim(strip_tags($data[$field]));
        }
        $data['sku'] = strtoupper($data['sku']);
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }
}
