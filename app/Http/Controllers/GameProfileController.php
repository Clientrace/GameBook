<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Carbon\Carbon;

use App\Game;

use App\Account;

use App\UserInfo;

use App\Comment;

use App\GameLike;

use App\CommentLike;

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
        $likes = GameLike::where('gameid',$id);
        $likes = $likes->where('liked',true)->count();
        $dislikes = GameLike::where('gameid',$id);
        $dislikes = $dislikes->where('liked',false)->count();
    	return view("pages.game_profile",compact('game','dev','devinfo','log','user','comments','acc','accs','accsinfo','likes','dislikes'));
    }

    public function addcomment(Request $request, $id){
    	$log = $request->session()->get('log');

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

    	return redirect("/game/".$id);
    }

    public function gamelike(Request $request,$id){
        $log = $request->session()->get('log');
        $game = Game::find($id);
        $dev = Account::find($game->dev_id);
        $accs = Account::all(); 
        $accsinfo = UserInfo::all();
        $acc = Account::find($request->session()->get('userid'));
        $user = UserInfo::where('user_id',$request->session()->get('userid'))->get();
        $devinfo = UserInfo::where('user_id',$game->dev_id)->get();
        $likes = GameLike::where('gameid',$id);
        $likes = $likes->where('liked',true)->count();
        $dislikes = GameLike::where('gameid',$id);
        $dislikes = $dislikes->where('liked',false)->count();
        $comments = Comment::where('gameid',$id)->orderBy('date_created','desc')->get();

        if(!$log)
            return view("pages.game_profile",compact('game','dev','devinfo','log','user','comments','acc','accs','accsinfo','likes','dislikes'));

        $gl = GameLike::where("gameid",$id)->get();
        $liker = $gl->where('userid',$request->session()->get("userid"))->first();
        if($liker!=null){
            if(!$liker->liked)
                GameLike::where("userid",$request->session()->get("userid"))->update(array('liked'=>true));
        }
        else{
            $game_like = new GameLike;
            $game_like->userid = $request->session()->get("userid");
            $game_like->gameid = $id;
            $game_like->liked = true;
            $game_like->save();   
        }
        return redirect("/game/".$id);
    }

    public function gamedislike(Request $request,$id){
        $log = $request->session()->get('log');
        $game = Game::find($id);
        $dev = Account::find($game->dev_id);
        $accs = Account::all(); 
        $accsinfo = UserInfo::all();
        $acc = Account::find($request->session()->get('userid'));
        $user = UserInfo::where('user_id',$request->session()->get('userid'))->get();
        $devinfo = UserInfo::where('user_id',$game->dev_id)->get();
        $likes = GameLike::where('gameid',$id);
        $likes = $likes->where('liked',true)->count();
        $dislikes = GameLike::where('gameid',$id);
        $dislikes = $dislikes->where('liked',false)->count();
        $comments = Comment::where('gameid',$id)->orderBy('date_created','desc')->get();

        if(!$log)
            return view("pages.game_profile",compact('game','dev','devinfo','log','user','comments','acc','accs','accsinfo','likes','dislikes'));

        $gl = GameLike::where("gameid",$id)->get();
        $liker = $gl->where('userid',$request->session()->get("userid"))->first();
        if($liker!=null){
            if($liker->liked)
                GameLike::where("userid",$request->session()->get("userid"))->update(array('liked'=>false));
        }
        else{
            $game_like = new GameLike;
            $game_like->userid = $request->session()->get("userid");
            $game_like->gameid = $id;
            $game_like->liked = false;
            $game_like->save();   
        }
        return redirect("/game/".$id);
    }

    public function commentlike(Request $request,$g_id,$c_id){
        $log = $request->session()->get('log');
        $game = Game::find($g_id);
        $dev = Account::find($game->dev_id);
        $accs = Account::all(); 
        $accsinfo = UserInfo::all();
        $acc = Account::find($request->session()->get('userid'));
        $user = UserInfo::where('user_id',$request->session()->get('userid'))->get();
        $devinfo = UserInfo::where('user_id',$game->dev_id)->get();
        $likes = GameLike::where('gameid',$g_id);
        $likes = $likes->where('liked',true)->count();
        $dislikes = GameLike::where('gameid',$g_id);
        $dislikes = $dislikes->where('liked',false)->count();
        $comments = Comment::where('gameid',$g_id)->orderBy('date_created','desc')->get();

        if(!$log)
            return view("pages.game_profile",compact('game','dev','devinfo','log','user','comments','acc','accs','accsinfo','likes','dislikes'));

        $cl = CommentLike::where("commentid",$c_id)->get();
        $liker = $cl->where('userid',$request->session()->get("userid"))->first();
        if($liker==null){
            $comment_likes = new CommentLike;
            $comment_likes->userid = $request->session()->get("userid");
            $comment_likes->commentid = $c_id;
            $comment_likes->liked = true;
            $comment_likes->save();
            $cm = Comment::find($c_id);
            Comment::where("id",$c_id)->update(array('like'=>($cm->like+1)));
            $userinfo = UserInfo::where('user_id',$cm->userid)->first();
            UserInfo::where("user_id",$cm->userid)->update(array('experience'=>($userinfo->experience+100)));
        }
        return redirect("/game/".$g_id);
    }
}
