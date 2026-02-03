<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the user's profile
     */
    public function index()
    {
        return view('profile.index');
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'The provided password does not match your current password.',
            'new_password.confirmed' => 'The password confirmation does not match.',
            'new_password.min' => 'The new password must be at least 8 characters.',
        ]);

        // Update the password
        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile')
            ->with('password_success', 'Your password has been updated successfully!');
    }
}
