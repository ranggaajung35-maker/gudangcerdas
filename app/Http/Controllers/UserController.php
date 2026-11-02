<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:150'],
            'role' => ['nullable', Rule::in(['admin', 'staff'])],
        ]);
        $term = trim($filters['q'] ?? '');
        $users = User::query()
            ->when($term !== '', function ($q) use ($term) {
                $q->where(fn ($sub) => $sub->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%"));
            })
            ->when(! empty($filters['role']), fn ($q) => $q->where('role', $filters['role']))
            ->latest()->paginate(12)->withQueryString();
        return view('users.index', compact('users'));
    }

    public function create(): View { return view('users.create', ['account' => new User()]); }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100', 'not_regex:/<[^>]*>/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:25', 'regex:/^[0-9+(). -]*$/'],
            'role' => ['required', Rule::in(['admin', 'staff'])],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'max:72'],
        ]);
        $data['name'] = trim(strip_tags($data['name']));
        $data['email'] = strtolower($data['email']);
        User::create($data);
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    public function edit(User $user): View { return view('users.edit', ['account' => $user]); }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100', 'not_regex:/<[^>]*>/'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:25', 'regex:/^[0-9+(). -]*$/'],
            'role' => ['required', Rule::in(['admin', 'staff'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed', 'max:72'],
        ]);

        if ($request->user()->id === $user->id && $data['role'] !== 'admin') {
            return back()->withInput()->with('error', 'Anda tidak dapat mengubah peran akun sendiri.');
        }
        if ($user->isAdmin() && $data['role'] !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->withInput()->with('error', 'Minimal harus ada satu akun Admin.');
        }

        $data['name'] = trim(strip_tags($data['name']));
        $data['email'] = strtolower($data['email']);
        if (empty($data['password'])) unset($data['password']);
        $user->update($data);
        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Admin terakhir tidak dapat dihapus.');
        }
        if ($user->stockMovements()->exists()) {
            return back()->with('error', 'Pengguna memiliki riwayat transaksi. Nonaktifkan atau ubah perannya, bukan menghapus akun.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
