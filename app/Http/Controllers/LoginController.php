<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Account;

class LoginController extends Controller
{
    //
    public function index(){
    	return view('pages.login');
    }

    public function login(Request $request){
    	$accounts = Account::where('username',$request['username'])->get();
    	$accounts = $accounts->where('password',$request['password']);
    	if($accounts->all()==null)
    		return "no account";
    	return $accounts;
    }

}