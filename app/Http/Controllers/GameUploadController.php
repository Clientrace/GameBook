<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class GameUploadController extends Controller
{
    public function index(){
    	return view('pages.game_upload');
    }

    public function store(Reqeuest $request){
    	$file = $request->file('file');
    	if($request->file('file')->isValid()){
    		$zip = new ZipArchive;
    		$res = $zip->open($file);
    		if($res){
    			$zip->extractTo('temp/');
    			$zip->close();
    		}
    	}
    }
}
