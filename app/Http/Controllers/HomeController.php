<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\Models\Bubble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('welcome');
    }

    public function deepLink($id){
        Log::debug('bubble deeplink id'.$id);
        $bubble = Bubble::with('user')->find($id);
        Log::debug('bubble deeplink'.$bubble);
        return view('welcome',compact('bubble'));

    }
}
