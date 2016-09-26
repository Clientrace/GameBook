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
    }
}
