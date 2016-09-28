<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\UserInfo;

use App\Comment;

use App\Account;

use App\Game;

use App\GameLike;

class UserProfileController extends Controller
{
    public function index(Request $request){
    	$id = $request->session()->get('userid');
    	$log = $request->session()->get('log');
        $games = Game::all();
        $likes = GameLike::where('userid',$id)->get();
        $likes = $likes->where('liked',true);
        $dislikes = GameLike::where('userid',$id)->get();
        $dislikes = $dislikes->where('liked',false);
        $accsinfo = UserInfo::all();
        $comments = Comment::where('userid',$id)->get();
    	if(!$log)
    		return redirect("/");
    	$user = UserInfo::where("user_id",$id)->get();
    	$acc = Account::find($id);
    	return view("pages.user_profile",compact('user','acc','comments','games','accsinfo','likes','dislikes'));
    }

    public function updatePic(Request $request){
    	$file = $request->file('file');
    	if ($request->file('file')->isValid()) {
    		$done = false;
    		$filename = '';
    		while(!$done){
	    		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			    $charactersLength = strlen($characters);
			    $randomString = '';
			    for ($i = 0; $i < 10; $i++) {
			        $randomString .= $characters[rand(0, $charactersLength - 1)];
			    }
	    		$filename = $randomString;
	    		//check if picname is already taken:
	    		$name = UserInfo::where('picname',$randomString)->get();
	    		if(count($name)==0)
	    			$done = true;
    		}
    		$request->file('file')->move("assets/userdp", $randomString);
    		$id = $request->session()->get('userid');
    		$user = UserInfo::where('user_id',$id)->update(array('picname'=>$filename));
		}


        $id = $request->session()->get('userid');
        $user = UserInfo::where("user_id",$id)->get();
        $comments = Comment::where('userid',$id)->get();
        $likes = GameLike::where('userid',$id)->get();
        $likes = $likes->where('liked',true);
        $dislikes = GameLike::where('userid',$id)->get();
        $dislikes = $dislikes->where('liked',false);
        $acc = Account::find($id);
        $games = Game::all(); 
        $accsinfo = UserInfo::all();
        return view("pages.user_profile",compact('user','acc','comments','games','accsinfo','likes','dislikes'));
    }
}
