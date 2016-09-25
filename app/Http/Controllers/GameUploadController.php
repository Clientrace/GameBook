<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class GameUploadController extends Controller
{
    public function index(){
    	return view('pages.game_upload');
    }

    public function store(){

    }
}
