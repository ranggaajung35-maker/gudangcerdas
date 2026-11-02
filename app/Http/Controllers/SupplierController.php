<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:150']]);
        $term = trim($filters['q'] ?? '');
        $suppliers = Supplier::withCount('products')
            ->when($term !== '', function ($query) use ($term) {
                $query->where(fn ($q) => $q->where('company_name', 'like', "%{$term}%")->orWhere('contact_name', 'like', "%{$term}%"));
            })->orderBy('company_name')->paginate(10)->withQueryString();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create(): View { return view('suppliers.create', ['supplier' => new Supplier()]); }
    public function store(Request $request): RedirectResponse
    {
        Supplier::create($this->validated($request));
        return redirect()->route('suppliers.index')->with('success', 'Pemasok berhasil ditambahkan.');
    }
    public function edit(Supplier $supplier): View { return view('suppliers.edit', compact('supplier')); }
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($this->validated($request));
        return redirect()->route('suppliers.index')->with('success', 'Data pemasok berhasil diperbarui.');
    }
    public function destroy(Supplier $supplier): RedirectResponse
    {
        // Produk yang terkait tetap aman karena supplier_id menggunakan ON DELETE SET NULL.
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Pemasok berhasil dihapus.');
    }
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'min:2', 'max:150', 'not_regex:/<[^>]*>/'],
            'contact_name' => ['nullable', 'string', 'max:100', 'not_regex:/<[^>]*>/'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25', 'regex:/^[0-9+(). -]*$/'],
            'address' => ['nullable', 'string', 'max:1500', 'not_regex:/<[^>]*>/'],
        ]);
        foreach (['company_name', 'contact_name', 'address'] as $field) {
            if (isset($data[$field])) $data[$field] = trim(strip_tags($data[$field]));
        }
        return $data;
    }
}
