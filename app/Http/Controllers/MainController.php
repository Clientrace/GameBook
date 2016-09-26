<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\UserInfo;

use App\Game;

class MainController extends Controller
{
    public function index(Request $request){

        $topgames = Game::all();

        $recentgames = Game::all();

    	$log = $request->session()->get('log');
    	$id = $request->session()->get('userid');
    	$user = UserInfo::where("user_id",$id)->get();
    	return view("pages.main",compact('log','user','topgames','recentgames'));
        
    }

    public function logout(Request $request){
    	$request->session()->forget('userid');
    	$request->session()->put('log',false);
    	$log = false;

        $topgames = Game::all();

        $recentgames = Game::all();

        $user = UserInfo::where("user_id",$id)->get();
    	return view("pages.main",compact('log','user','topgames','recentgames'));
    }
}
