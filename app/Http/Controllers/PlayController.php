<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PlayController extends Controller
{
    public function index(Request $request, $name){
    	$log = $request->session()->get('log');
    	if($log)
    		return view("/games/".$name."/index");
    	else
    		return redirect("/login");
    }
}
