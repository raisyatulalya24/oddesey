<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit() { return view('profile'); }

    public function update(Request $r)
    {
        $u = $r->user();
        $d = $r->validate(['name' => 'required|max:100', 'email' => ['required', 'email', Rule::unique('users')->ignore($u->id)], 'password' => 'nullable|min:8|confirmed']);
        if (blank($d['password'] ?? null)) unset($d['password']);
        $u->update($d);
        return back()->with('ok', 'Profil diperbarui.');
    }
}
