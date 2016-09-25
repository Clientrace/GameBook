<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Account;


class LoginController extends Controller
{
    //
    public function index(){
    	$regi = false;
    	return view('pages.login',compact('regi'));
    }

}