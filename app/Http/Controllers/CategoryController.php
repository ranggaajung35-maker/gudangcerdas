<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::withCount('products')
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.trim($request->string('q')).'%'))
            ->orderBy('name')->paginate(10)->withQueryString();
        return view('categories.index', compact('categories'));
    }

    public function create(): View { return view('categories.create', ['category' => new Category()]); }
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        Category::create($data);
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category): View { return view('categories.edit', compact('category')); }
    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update($this->validated($request, $category));
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh produk.');
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100', 'not_regex:/<[^>]*>/', Rule::unique('categories', 'name')->ignore($category?->id)],
            'description' => ['nullable', 'string', 'max:1000', 'not_regex:/<[^>]*>/'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['name'] = trim(strip_tags($data['name']));
        $data['description'] = isset($data['description']) ? trim(strip_tags($data['description'])) : null;
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }
}
