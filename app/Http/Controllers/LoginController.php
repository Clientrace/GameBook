<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Account;

use App\UserInfo;


class LoginController extends Controller
{
    //
    public function index(Request $request){
    	$regi = false;
    	$log = $request->session()->get('log');
    	$id = $request->session()->get('userid');
    	$user = UserInfo::where("user_id",$id)->get();
    	if($log)
    		return view('pages.home',compact('log','user'));
    	return view('pages.login',compact('regi'));
    }

}