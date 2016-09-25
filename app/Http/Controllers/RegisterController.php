<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Carbon\Carbon;

use App\Account;

use App\UserInfo;

class RegisterController extends Controller
{
    //Registration Page Controller

    public function index(){
    	$date = Carbon::now();
    	$splitDate = preg_split('{-}', $date);
    	$year = (int) $splitDate[0];
    	return view("pages.register", compact('year'));
    }

    public function store(Request $request){
    	$account = new Account;
    	$account->username = $request['username'];
    	$account->password = $request['password'];
    	if($request['type']=="USER")
    		$account->type = 0;
    	else if($request['type']=="DEVELOPER")
    		$account->type = 1;
    	$account->created = Carbon::now();
    	$account->save();

        $userinfo = new UserInfo;
        $userinfo->user_id = $account->id;
        $userinfo->first_name = $request['first_name'];
        $userinfo->last_name = $request['last_name'];
        $userinfo->experience = 0;
        $birthdate = $request['month']." ".$request['day']." ".$request['year'];
    	$userinfo->birthdate = $birthdate;
        $userinfo->save();

        return $userinfo->all();
    }

}
