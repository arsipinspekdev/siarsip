<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

final class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(): View
    {
        $roles = Role::withCount('users')->orderBy('name')->get();

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        return view('roles.create');
    }

    /**
     * Store a newly created role in database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:roles,name'],
        ], [
            'name.required' => 'Nama wewenang / role wajib diisi.',
            'name.unique'   => 'Nama wewenang / role tersebut sudah ada.',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $role = Role::create($validated);

        return redirect()
            ->route('roles.index')
            ->with('success', "Wewenang (Role) '{$role->name}' berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the role.
     */
    public function edit(Role $role): View
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the role in database.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('roles')->ignore($role->id)],
        ], [
            'name.required' => 'Nama wewenang / role wajib diisi.',
            'name.unique'   => 'Nama wewenang / role tersebut sudah ada.',
        ]);

        if ($role->slug !== 'administrator') {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $role->update($validated);

        return redirect()
            ->route('roles.index')
            ->with('success', "Wewenang (Role) '{$role->name}' berhasil diperbarui.");
    }

    /**
     * Remove the role from database.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->slug === 'administrator') {
            return back()->with('error', 'Role Administrator Utama adalah bawaan sistem dan tidak dapat dihapus.');
        }

        if ($role->users()->exists()) {
            return back()->with('error', "Role '{$role->name}' tidak dapat dihapus karena masih digunakan oleh beberapa akun pengguna.");
        }

        $name = $role->name;
        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', "Role '{$name}' berhasil dihapus.");
    }
}
