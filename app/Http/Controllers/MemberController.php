<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function index()
    {
        $members = User::orderBy('role')->orderBy('username')->paginate(10);

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', 'min:6'],
            'role'     => ['required', Rule::in(['Ketua', 'Humas', 'Bendahara'])],
            'status'   => ['required', Rule::in(['Aktif', 'Nonaktif'])],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('members.index')->with('success', 'User Admin berhasil ditambahkan.');
    }

    public function edit(User $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, User $member)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($member->id_user, 'id_user')],
            'role'     => ['required', Rule::in(['Ketua', 'Humas', 'Bendahara', 'Siswa'])],
            'status'   => ['required', Rule::in(['Aktif', 'Nonaktif'])],
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['min:6']]);
            $data['password'] = Hash::make($request->password);
        }

        $member->update($data);

        return redirect()->route('members.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $member)
    {
        $member->delete();

        return redirect()->route('members.index')->with('success', 'Data user berhasil dihapus.');
    }
}

