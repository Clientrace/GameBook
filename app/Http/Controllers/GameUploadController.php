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
    	$filename = "temp/"+$request->file('file')->getClientOriginalName();
    	if ($request->file('file')->isValid()) {
    		$done = false;
    		$request->file('file')->move("temp/", "game.zip");
		}

    	$zip = new \ZipArchive();
    	$zip->open("temp/game.zip");
    	$zip->extractTo('temp/');
    	$zip->close();
    }
}
