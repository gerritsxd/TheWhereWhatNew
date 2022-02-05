<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\InvitationCode;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/email/verify';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function showRegistrationForm(Request $request)
    {
        $invitation_code =is_null($request->invitation_code)?'': $request->invitation_code;

        $invitation_code_valid = InvitationCode::where('invitation_code',$invitation_code)->where('used',false)->get();

        return view('auth.register',compact('invitation_code_valid'));
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        for ($number_of_invitations = 0 ; $number_of_invitations < 10; $number_of_invitations++){
           $invitation_code = new InvitationCode();
           $invitation_code->user_id = $user->id;
           $invitation_code->invitation_code = Str::uuid();
           $invitation_code->save();
        }
        $used_invitation_code = InvitationCode::where('invitation_code',$data['invitation_code'])->first();
        $used_invitation_code->used = true;
        $used_invitation_code->user_id = $user->id;
        $used_invitation_code->save();

        return $user;
    }
}
