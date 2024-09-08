<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Import this line

use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'User is not authenticated.'], 401);
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'Password changed successfully.'], 200);
    }

    public function forgotPassword(Request $request)
{
    $request->validate(['phone' => 'required|digits:10']);

    $user = User::where('phone', $request->phone)->first();

    if (!$user) {
        return response()->json(['phone' => ['Phone number not found.']], 404);
    }

    // Generate a 4-digit numeric token and send it via SMS (assuming SMS integration is set up)
    $token = rand(1000, 9999); // Generate a random 4-digit number
    // Example: SMS::send($user->phone, "Your reset code is: $token");

    $user->password_reset_token = $token;
    $user->save();

    return response()->json(['message' => 'Password reset code sent via SMS.'], 200);
}

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('phone', $request->phone)
                    ->where('password_reset_token', $request->token)
                    ->first();

        if (!$user) {
            return response()->json(['phone' => ['Invalid token or phone number.']], 400);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'password_reset_token' => null, // Clear the token after successful reset
        ])->save();

        return response()->json(['message' => 'Your password has been reset!'], 200);
    }
}
