<?php

namespace App\Http\Controllers\Manager\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


// controller作成時ここからコピー↓
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Illuminate\Support\Facades\File;
// controller作成時ここまでコピー↑

// Model ↓
use App\Models\MAddressModel;
use App\Models\MEmployerModel;
use App\Models\MSubKindModel;



// Model ↑

// Request ↓
use App\Http\Requests\MEmployerRequest;
// Request ↑

class MEmployerUserController extends Controller
{
    function index(Request $request)
	{

	
        $demo = "";
		return view('Manager.Screen.Master.MEmployerUser.index', compact('demo'));


	}
}
