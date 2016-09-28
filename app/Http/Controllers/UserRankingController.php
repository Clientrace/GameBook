<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\UserInfo;

class UserRankingController extends Controller
{
    public function index(){
    	$accs = UserInfo::orderBy('experience','desc')->get();
    	return view("pages.user_ranking",compact('accs'));
    }
}
