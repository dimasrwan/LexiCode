<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update informasi profil (Nama/Email).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Hapus akun user.
     * Perbaikan: Mendukung user Google Auth yang tidak punya password.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        /**
         * LOGIC SECURITY:
         * Jika user mendaftar lewat Google (password kosong/acak di sistem), 
         * kita tidak mewajibkan pengecekan password agar mereka tidak terjebak.
         * Kita asumsikan mereka sudah terverifikasi lewat sesi login aktif.
         */
        if ($user->password) {
            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', 'ACCOUNT_TERMINATED_SUCCESSFULLY');
    }
}