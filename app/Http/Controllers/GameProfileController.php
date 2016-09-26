<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Game;

use App\Account;

use App\UserInfo;

class GameProfileController extends Controller
{
    public function index(Request $request, $id){
    	$log = $request->session()->get('log');
    	$game = Game::find($id);
    	$dev = Account::find($game->dev_id);
    	$user = UserInfo::where('user_id',$request->session()->get('userid'))->get();
    	$devinfo = UserInfo::where('user_id',$game->dev_id)->get();
    	return view("pages.game_profile",compact('game','dev','devinfo','log','user'));
    }
}
