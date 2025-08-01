<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    function index(Request $request)
	{

        return redirect(route('manager.dashboard'));
	}

    function dashboard(Request $request)
	{

        $demo = "";
		return view('Manager.Screen.dashboard', compact('demo'));
	
	}
}
