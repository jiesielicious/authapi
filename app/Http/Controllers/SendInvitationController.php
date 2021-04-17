<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InviteUser;
use Illuminate\Validation\ValidationException;

class SendInvitationController extends Controller
{
    //

    public function sendinvitationlink(Request $request) {
        
        $request->validate([
            'email' => 'required|unique:users'
        ]);

        Mail::to($request->email)->send(new InviteUser());
    }
}
