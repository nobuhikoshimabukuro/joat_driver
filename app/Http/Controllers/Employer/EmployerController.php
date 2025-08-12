<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    function index(Request $request)
	{

        return redirect(route('employer.dashboard'));
	}

    function dashboard(Request $request)
	{

        $demo = "";
		return view('Employer.Screen.dashboard', compact('demo'));
	
	}
}
