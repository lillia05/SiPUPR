<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['pupr', 'cabang']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%$search%");
            });
        }

        if ($request->filled('jabatan')) {
            $query->where('role', $request->jabatan);
        }

        $users = $query->latest()->paginate($request->input('per_page', 10));

        return view('pupr.users.index', compact('users'));
    }


    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('pupr.users.show', compact('user'));
    }

    public function create()
    {
        return view('pupr.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'role'     => ['required', 'in:pupr,cabang'], 
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'username' => $request->username,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('pupr.users.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pupr.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'role'     => ['required', 'in:pupr,cabang'],
        ]);

        $data = [
            'username' => $request->username,
            'role'     => $request->role,
        ];

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('pupr.users.index')->with('success', 'Data pengguna diperbarui.');
    }
    

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('pupr.users.index')->with('success', 'User berhasil dihapus.');
    }
}