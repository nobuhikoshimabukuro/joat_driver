<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
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
