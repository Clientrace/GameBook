<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Account;

use App\UserInfo;


class HomeController extends Controller
{
    //
    public function index(Request $request){
    	$userid = $request->session()->get('userid');
        $account = Account::find($userid);
        $userinfo = UserInfo::where('user_id',$userid)->get();

        return view("pages.home",compact('userinfo'),compact('account'));
    }

    public function login(Request $request){
    	$accounts = Account::where('username',$request['username'])->get();
    	$accounts = $accounts->where('password',$request['password']);
        
    	if($accounts->all()==null)
    		return view("pages.login");

        $request->session()->put('userid',$accounts[0]->id);
        $request->session()->put('usertype',$accounts[0]->type);
        
        $userid = $request->session()->get('userid');
        $account = Account::find($userid);
        $userinfo = UserInfo::where('user_id',$userid)->get();

        return view("pages.home",compact('userinfo'),compact('account'));
    }
}
