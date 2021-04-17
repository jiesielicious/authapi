<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class VerifyController extends Controller
{
    //
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
        return ( !$user ) ? true : false;
    }
}
