<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    //


    public function login(Request $request) {

        $request->validate([
            'user_name' => 'required',
            'password' => 'required',
        ]);
    
        $user = User::where('user_name', $request->user_name)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password) || ! $user->email_verified_at) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
                'email_verified_at' => ['Please Verify your Email!']
            ]);
        }
    
        return $user->createToken('auth')->plainTextToken;
    }

    //Send Invitation Link with Router/API URL for Signup
    //Insert User Details with Verification PIN
    //Send Email with Verification PIN
    //Validate PIN date

}
