<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Carbon\Carbon;

class RegisterController extends Controller
{
    //Registration Page Controller

    public function index(){
    	$date = Carbon::now();
    	$splitDate = preg_split('{-}', $date);
    	$year = (int) $splitDate[0];
    	return view("pages.register", compact('year'));
    }

    public function sphtore(Request $request){
    	$account = new Accounts();
    	$account->username = $request[''];
    }

}
