<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Carbon\Carbon;

use App\Game;

use App\Account;

class GameUploadController extends Controller
{
    public function index(Request $request){
        $page = 0;
        $type = Account::find($request->session()->get('userid'));
        $type = $type->type;
        if($type==1)
    	   return view('pages.game_upload',compact('page'));
        else
            return redirect("/");
    }

    public function store(Request $request){
    	$filename = "game_uploads/"+$request->file('file')->getClientOriginalName();
    	if ($request->file('file')->isValid()) {
    		$done = false;
    		$request->file('file')->move("game_uploads/", "game.zip");
		}

    	$zip = new \ZipArchive();
    	$res = $zip->open("game_uploads/game.zip");
        if($res){
            $zip->extractTo('game_uploads');
            $zip->close();
        }
        unlink("game_uploads/game.zip");
        $page = 2;
        return view('pages.game_upload',compact('page'));
    }

    public function save(Request $request){
        //picture name
        $game = new Game;
        $file = $request->file('file');
        $filename = '';
        if($request->file('file')->isValid()){
            $done = false;
            while(!$done){
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < 10; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                $filename = $randomString;
                $name = Game::where("picname",$randomString)->get();
                if(count($name)==0)
                    $done = true;
            }
            $request->file('file')->move("assets/gamelogo",$randomString);
        }

        $game->dev_id=$request->session()->get('userid');
        $game->name=$request['game_name'];
        $game->views=0;
        $game->description=$request['description'];
        $game->genre=$request['genre'];
        $game->picname = $filename;
        $game->reviewed = false;
        $game->date_created = Carbon::now();
        $game->save();
        
        $page = 3;
        return view('pages.game_upload',compact('page'));
    }

    public function gameupload(){
        $page = 1;
        return view('pages.game_upload',compact('page'));
    }

    public function gamedesc(){
        $page = 2;
        return view('pages.game_upload',compact('page'));
    }
}
