<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Mail\InviteUser;
use App\Mail\Verification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    //


    public function login(Request $request) {

        $request->validate([
            'user_name' => 'required',
            'password' => 'required',
        ]);
    
        $user = User::where('user_name', $request->user_name)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        return $user->createToken('auth')->plainTextToken;
    }


    public function sendinvitationlink(Request $request) {
        
        $request->validate([
            'email' => 'required|unique:users'
        ]);

        Mail::to($request->email)->send(new InviteUser());
    }

    public function signup(Request $request) {

        $request->validate([
            "name" => "required",
            "user_name" => "required|unique:users",
            "password" => "required",
            "email" => "required|unique:users",
        ]);

        $user = User::create([
            'name' => $request->name,
            'user_name' => $request->user_name,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'verification_pin' => Str::random(6),
            'avatar' => 'test.jpg',
            'user_role' => 'user'
        ]);
        
        if( $user ):
            $verificationPin = $this->getVerificationPin( $user->id );
            Mail::to($request->email)->send(new Verification($verificationPin));
        endif;

    }

    public function getVerificationPin($id) {
        $user = User::find($id);
        return $user->verification_pin;
    }

    //Send Invitation Link with Router/API URL for Signup
    //Insert User Details with Verification PIN
    //Send Email with Verification PIN
    //Validate PIN date

}
