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
        $log = $request->session()->get('log');

        if($log==false)
            return redirect("/");

        $account = Account::find($userid);
        $user = UserInfo::where('user_id',$userid)->get();

        return view("pages.home",compact('user','account','log'));
    }

    public function login(Request $request){
    	$accounts = Account::where('username',$request['username'])->get();
    	$accounts = $accounts->where('password',$request['password']);
        
    	if($accounts->all()==null){
            $regi = false;
    		return view("pages.login",compact('regi'));
        }

        $request->session()->put('userid',$accounts[0]->id);
        $request->session()->put('usertype',$accounts[0]->type);
        $request->session()->put('log',true);
        
        $userid = $request->session()->get('userid');
        $account = Account::find($userid);
        $user = UserInfo::where('user_id',$userid)->get();

        $log = true;
        
        return view("pages.home",compact('user','account','log'));
    }

}
