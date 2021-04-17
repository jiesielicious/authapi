<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\Verification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //
    public function update(Request $request, $id) {
        $user = User::where('id',$id)->update($request->all());
        return response()->json(array('message' => 'Successfully Updated!'));
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
            $emailaddress = $this->getEmail( $user->id );
            Mail::to($request->email)->send(new Verification($verificationPin, $emailaddress));
        endif;

    }

    public function getVerificationPin($id) {
        $user = User::find($id);
        return $user->verification_pin;
    }

    public function getEmail($id) {
        $user = User::find($id);
        return $user->email;
    }
}
