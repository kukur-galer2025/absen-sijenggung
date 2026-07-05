<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role !== 'admin') abort(403);
        $employees = User::where('role', 'pegawai')->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function create(Request $request)
    {
        if ($request->user()->role !== 'admin') abort(403);
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'nip' => ['nullable', 'string', 'max:50'],
            'jabatan' => ['nullable', 'string', 'max:100'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pegawai',
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(Request $request, User $employee)
    {
        if ($request->user()->role !== 'admin') abort(403);
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        if ($request->user()->role !== 'admin') abort(403);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',id,'.$employee->id],
            'nip' => ['nullable', 'string', 'max:50'],
            'jabatan' => ['nullable', 'string', 'max:100'],
        ]);

        $employee->update([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
        ]);

        if ($request->filled('password')) {
            $employee->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Request $request, User $employee)
    {
        if ($request->user()->role !== 'admin') abort(403);
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}
