<?php

namespace App\Http\Controllers\Manager\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MCompanyController extends Controller
{
    function index(Request $request)
	{

        $demo = "";
		return view('Manager.Screen.Master.MCompany.index', compact('demo'));


	}

}
