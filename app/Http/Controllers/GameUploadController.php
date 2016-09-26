<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class GameUploadController extends Controller
{
    public function index(){
    	return view('pages.game_upload');
    }

    public function store(Request $request){
    	$filename = "game_upload/"+$request->file('file')->getClientOriginalName();
    	if ($request->file('file')->isValid()) {
    		$done = false;
    		$request->file('file')->move("game_upload/", "game.zip");
		}

    	$zip = new \ZipArchive();
    	$res = $zip->open("game_upload/game.zip");
        if($res){
            $zip->extractTo('game_uploads');
            $zip->close();
        }
        unlink("game_upload/game.zip");
    }
}
