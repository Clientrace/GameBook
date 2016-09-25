<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\UserInfo;

use App\Account;

class UserProfileController extends Controller
{
    public function index(Request $request){
    	$id = $request->session()->get('userid');
    	$user = UserInfo::where("user_id",$id)->get();
    	$acc = Account::find($id);
    	return view("pages.user_profile",compact('user','acc'));
    }
    public function store(Request $request){
    	$file = $request('file');
    }
}
