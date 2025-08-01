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
use App\Models\MCompanyModel;



// Model ↑

// Request ↓
use App\Http\Requests\session_confirmation_request;
// Request ↑

class MCompanyController extends Controller
{
    function index(Request $request)
	{


		$prefecture_info = MAddressModel::GetPrefectureInfo();
        
		return view('Manager.Screen.Master.MCompany.index', compact('prefecture_info'));


	}

}
