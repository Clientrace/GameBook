<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PlayController extends Controller
{
    public function index($name){
    	return view("/games/".$name."/index");
    }
}
