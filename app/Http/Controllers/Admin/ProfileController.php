<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('pages.admin.profile.index', [
            'title'    => 'Ubah Profil',
            'userData' => auth()->user()
        ]);
    }

    public function update(ProfileRequest $request, User $user)
    {
        $user = Auth::user();
        $validate = $request->validated();
        if ($validate['oldPassword'] ?? false) {
            //check password
            if (Hash::check($validate['oldPassword'], $user->password)) {
                // password match
                $newPass = Hash::make($validate['password']);
                User::where('id', $user->id)->update(['password' => $newPass]);
                return redirect('/dashboard/pengguna/profile')->with('success', 'Kata Sandi Anda Telah Diperbarui!');
            } else {
                return redirect('/dashboard/pengguna/profile')->with('failed', 'Kata Sandi Lama Anda Tidak Valid!');
            }
        }
        User::where('id', $user->id)->update($validate);
        return redirect('/dashboard/pengguna/profile')->with('success', "Profil Anda Telah Diperbarui!");
    }
}