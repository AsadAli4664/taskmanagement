<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    public function change(Request $request)
    {
        // Validate the request data
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'password_confirmation'=>'required|min:6'
        ]);
if($request->new_password != $request->password_confirmation){
    return redirect()->route('setting.index')->with(['error' => 'New Passwords Do not match']);
}
        // Get the currently authenticated user
        $user = $request->user();

        // Trim the input password and the hashed password before comparison
        $oldPassword = trim($request->old_password);
        $hashedPassword = trim($user->password);

        // Check if the provided old password matches the user's current password
        if (!Hash::check($oldPassword, $hashedPassword)) {
            throw ValidationException::withMessages(['old_password' => 'The provided old password is incorrect.']);
        }
       

        // Update the user's password
        $user->update(['password' => Hash::make($request->new_password)]);

        // Password change successful
        return redirect()->route('dashboard')->with('success', 'Password updated successfully.');
    }
}
