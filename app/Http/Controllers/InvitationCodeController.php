<?php

namespace App\Http\Controllers;

use App\Models\InvitationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationCodeController extends Controller
{
    //
    public function invite(){
        $invitation_code = InvitationCode::where('user_id',Auth::user()->id)->where('used',false)->first()->invitation_code;
        return view('invite',compact('invitation_code'));

    }
}
