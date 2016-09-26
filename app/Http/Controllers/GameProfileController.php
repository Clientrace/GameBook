<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Carbon\Carbon;

use App\Game;

use App\Account;

use App\UserInfo;

use App\Comment;

class GameProfileController extends Controller
{
    public function index(Request $request, $id){
    	$log = $request->session()->get('log');
    	$game = Game::find($id);
    	$dev = Account::find($game->dev_id);
        $accs = Account::all(); 
        $accsinfo = UserInfo::all();
    	$acc = Account::find($request->session()->get('userid'));
    	$user = UserInfo::where('user_id',$request->session()->get('userid'))->get();
    	$devinfo = UserInfo::where('user_id',$game->dev_id)->get();

    	$comments = Comment::where('gameid',$id)->orderBy('date_created','desc')->get();
    	return view("pages.game_profile",compact('game','dev','devinfo','log','user','comments','acc','accs','accsinfo'));
    }

    public function addcomment(Request $request, $id){
    	$log = $request->session()->get('log');
    	$game = Game::find($id);
    	$dev = Account::find($game->dev_id);
        $accs = Account::all(); 
        $accsinfo = UserInfo::all();
    	$user = UserInfo::where('user_id',$request->session()->get('userid'))->get();
    	$devinfo = UserInfo::where('user_id',$game->dev_id)->get();
    	$acc = Account::find($request->session()->get('userid'));
    	$comments = Comment::where('gameid',$id)->orderBy('date_created','desc')->get();

    	#adds comment:
    	if($log){
	    	$comment = new Comment;
	    	$comment->userid = $request->session()->get('userid');
	    	$comment->gameid = $id;
	    	$comment->description = $request['description'];
	    	$comment->like = 0;
	    	$comment->date_created = Carbon::now();
	    	$comment->save();
    	}
    	else{
    		$regi = false;
    		return view('pages.login',compact('regi'));
    	}

    	return view("pages.game_profile",compact('game','dev','devinfo','log','user','comments','acc','accs','accsinfo'));
    }
}
