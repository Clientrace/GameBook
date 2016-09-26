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
        $errorlist = "none";
    	return view("pages.register", compact('year'),compact('errorlist'));
    }
    
    /*
        Check Information Provided
        Errors:
            0 = Null fields
            1 = Password not match
            2 = Username allready taken
            3 = Name Fields wrong input
            4 = Username length
            5 = password length
    */

    public function store(Request $request){
        $errorlist = "none";

        if($request['password']==null){
            $errorlist = $errorlist."null_pass,";
        }

        if($request['cpassword']==null){
            $errorlist = $errorlist."null_cpass,";
        }

        if($request['first_name']==null){
            $errorlist = $errorlist."null_firstname,";
        }

        if($request['last_name']==null){
            $errorlist = $errorlist."null_lastname,";
        }

        if($request['password']!=$request['cpassword']){
            $errorlist = $errorlist."pass1,";
        }

        if(strlen($request['password'])<8)
            $errorlist = $errorlist."pass0,";

        $unames = Account::where('username',$request['username'])->get();
        
        if(count($unames)>0)
            $errorlist = $errorlist."uname1,";

        if(strlen($request['username'])<5)
            $errorlist = $errorlist."uname0,";

         if($request['username']==null){
            $errorlist = $errorlist."null_uname,";
        }

        if($request['accept']!='on')
            $errorlist = $errorlist."accept,";

        if($errorlist!='none'){
            $date = Carbon::now();
            $splitDate = preg_split('{-}', $date);
            $year = (int) $splitDate[0];
            return view("pages.register",compact('year','errorlist'));
        }

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
        $userinfo->picname = "dp-default.png";
        $userinfo->save();

        $regi = true;

        return view("pages.login",compact('regi'));
    }

}
