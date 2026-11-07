<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function account_list_view()
    {
        $users = User::where('role_id', 2)
            ->latest()
            ->paginate(10);

        return view('pages.account-list.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => 2, // Role 2 = Petugas Kandang / Karyawan Farm
            'status'   => 'approved',
            'no_hp'    => $request->no_hp,
            'alamat'   => $request->alamat,
        ]);

        return back()->with('success', 'Petugas Kandang baru berhasil ditambahkan!');
    }

    public function update_account_status(Request $request, $id)
    {
        $request->validate([
            'for' => ['required', Rule::in(['activate', 'deactivate'])],
        ]);

        $user = User::findOrFail($id);
        $action = $request->for;

        if ($action === 'activate') {
            $user->status = 'approved';
            $message = 'Akun petugas berhasil diaktifkan kembali.';
        } elseif ($action === 'deactivate') {
            $user->status = 'rejected';
            $message = 'Akun petugas berhasil dinonaktifkan.';
        }

        $user->save();

        return back()->with('success', $message);
    }
}