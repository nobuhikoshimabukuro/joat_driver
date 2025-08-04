<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    function index(Request $request)
	{

        return redirect(route('company.dashboard'));
	}

    function dashboard(Request $request)
	{

        $demo = "";
		return view('Company.Screen.dashboard', compact('demo'));
	
	}
}
