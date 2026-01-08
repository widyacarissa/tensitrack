<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DirectPasswordResetController extends Controller
{
    /**
     * Step 1: Show the email entry form.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Step 2: Validate email and show the password reset form directly.
     * WARNING: This skips email ownership verification. High security risk.
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['Email tidak ditemukan dalam sistem kami.'],
            ]);
        }

        // Jika email valid, langsung tampilkan form reset password
        // Kita kirim email ke view untuk disimpan di hidden input
        return view('auth.reset-password-direct', ['email' => $user->email]);
    }

    /**
     * Step 3: Update the password.
     */
    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return redirect()->route('login')->with('status', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}
