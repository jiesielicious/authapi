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
    
        if (! $user || ! Hash::check($request->password, $user->password) || ! $user->email_verified_at) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
                'email_verified_at' => ['Please Verify your Email!']
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

    public function verify(Request $request){
        $result = array();

        $request->validate([
            "email" => "required",
            "verification_pin" => "required",
        ]);

        $collection = User::where('email', $request->email)->where('verification_pin', $request->verification_pin)->first();
        
        if( !empty( $collection ) ):

            if( !$this->checkIfUserRegistered( $collection->id ) ):
                $user = User::where( 'id', $collection->id )->first();
                $user->email_verified_at = NOW();
                $user->save();

                $result["message"] = 'User Registered Successfully!';
            else:
                $result["message"] = 'User is already Registered!';
            endif;
            
        else:
            $result["message"] = 'Error Registering User!';
        endif;

        return response()->json($result);
    }

    public function checkIfUserRegistered( $id ) {
        $user = User::find($id)->first()->pluck('email_verified_at');
        return ( $user ) ? true : false;
    }

    public function update($id, Request $request) {
        $user = User::where('id',$id)->update($request->all());
        return response()->json(array('message' => 'Successfully Updated!'));
    }

    //Send Invitation Link with Router/API URL for Signup
    //Insert User Details with Verification PIN
    //Send Email with Verification PIN
    //Validate PIN date

}
