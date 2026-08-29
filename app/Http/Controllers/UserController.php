<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

final class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $query = User::with('role');

        if ($search) {
            $term = trim($search);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('username', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            });
        }

        $users = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $roles = Role::orderBy('name')->pluck('name', 'id')->toArray();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role_id'  => ['required', 'exists:roles,id'],
            'photo'    => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'name.required'     => 'Nama lengkap pengguna wajib diisi.',
            'username.required' => 'Nama pengguna (username) wajib diisi.',
            'username.unique'   => 'Username tersebut sudah terdaftar.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung (-), dan garis bawah (_).',
            'email.required'    => 'Alamat email resmi wajib diisi.',
            'email.unique'      => 'Alamat email tersebut sudah terdaftar.',
            'password.required' => 'Kata sandi akun wajib diisi.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
            'role_id.required'  => 'Wewenang / role pengguna wajib dipilih.',
            'photo.image'       => 'File foto profil harus berupa gambar yang valid.',
            'photo.max'         => 'Ukuran foto profil maksimal 2MB.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');
            $filename = 'avatar_' . Str::random(25) . '.' . $file->getClientOriginalExtension();
            $validated['photo'] = $file->storeAs('avatars', $filename, 'public');
        }

        $user = User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', "Akun pengguna untuk '{$user->name}' berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the user.
     */
    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->pluck('name', 'id')->toArray();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the user in database.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role_id'  => ['required', 'exists:roles,id'],
            'photo'    => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'name.required'     => 'Nama lengkap pengguna wajib diisi.',
            'username.required' => 'Nama pengguna (username) wajib diisi.',
            'username.unique'   => 'Username tersebut sudah terdaftar.',
            'email.required'    => 'Alamat email resmi wajib diisi.',
            'email.unique'      => 'Alamat email tersebut sudah terdaftar.',
            'password.min'      => 'Kata sandi baru minimal 6 karakter.',
            'role_id.required'  => 'Wewenang / role pengguna wajib dipilih.',
            'photo.max'         => 'Ukuran foto profil maksimal 2MB.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $file = $request->file('photo');
            $filename = 'avatar_' . Str::random(25) . '.' . $file->getClientOriginalExtension();
            $validated['photo'] = $file->storeAs('avatars', $filename, 'public');
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', "Data akun pengguna '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Remove the user from database (Soft Delete).
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', "Akun pengguna '{$name}' berhasil dinonaktifkan / dihapus.");
    }
}
