<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cari user di database, jika tidak ada, buat baru
            $user = User::updateOrCreate([
                'email' => $googleUser->email,
            ], [
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
                'password' => bcrypt('lexicode-secret-123') // dummy password
            ]);

            Auth::login($user);

            return redirect()->intended('dashboard');
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google.');
        }
    }
}