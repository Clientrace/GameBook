<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\UserInfo;

class MainController extends Controller
{
    public function index(Request $request){
    	$log = $request->session()->get('log');
    	$id = $request->session()->get('userid');
    	$user = UserInfo::where("user_id",$id)->get();
    	return view("pages.main",compact('log','user'));
    }

    public function logout(Request $request){
    	$request->session()->forget('userid');
    	$request->session()->put('log',false);
    	$log = false;

    	return view("pages.main",compact('log'));
    }
}
