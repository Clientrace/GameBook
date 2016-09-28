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
        $page = 0;
    	return view("pages.main",compact('log','user','topgames','recentgames','page'));
        
    }

    public function logout(Request $request){
    	$request->session()->forget('userid');
    	$request->session()->put('log',false);
        $regi = false;
    	return view("pages.login",compact('regi'));
    }

    public function puzzle(Request $request){
        $games = Game::where("genre","PUZZLE")->get();
        $log = $request->session()->get('log');
        $id = $request->session()->get('userid');
        $user = UserInfo::where("user_id",$id)->get();
        $page = 1;
        return view("pages.main",compact('log','user','games','recentgames','page'));
    }

    public function action(Request $request){
        $games = Game::where("genre","ACTION")->get();
        $log = $request->session()->get('log');
        $id = $request->session()->get('userid');
        $user = UserInfo::where("user_id",$id)->get();
        $page = 2;
        return view("pages.main",compact('log','user','games','recentgames','page'));
    }

    public function strategy(Request $request){
        $games = Game::where("genre","STRATEGY")->get();
        $log = $request->session()->get('log');
        $id = $request->session()->get('userid');
        $user = UserInfo::where("user_id",$id)->get();
        $page = 3;
        return view("pages.main",compact('log','user','games','recentgames','page'));
    }
}
