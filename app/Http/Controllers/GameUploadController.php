<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Carbon\Carbon;

use App\Game;

class GameUploadController extends Controller
{
    public function index(){
        $page = 0;
    	return view('pages.game_upload',compact('page'));
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
        if($request->file('file')->isValid()){
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
                $name = Game::where("picname",$randomString)->get();
                if(count($name)==0)
                    $done = true;
            }
            $request->file('file')->move("assets/gamelogo",$randomString);
            $game->picname = $filename;
        }

        $game->dev_id=$request->session()->get('userid');
        $game->name=$request['game_name'];
        $game->views=0;
        $game->likes=0;
        $game->dislikes=0;
        $game->description=$request['description'];
        $game->genre=$request['genre'];
        $game->reviewed = false;
        $game->date_created = Carbon::now();
        $game->save();
        return Game::all();
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
