<?php

namespace App\Http\Controllers\Manager\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MEmployerUserController extends Controller
{
    function index(Request $request)
	{

	
        $demo = "";
		return view('Manager.Screen.Master.MEmployerUser.index', compact('demo'));


	}
}
