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
use App\Http\Requests\session_confirmation_request;
// Request ↑

class MEmployerController extends Controller
{
    function index(Request $request)
	{


		$prefecture_info = MAddressModel::GetPrefectureInfo();
        
		$m_employer = MEmployerModel::get();
		return view('Manager.Screen.Master.MEmployer.index', compact('m_employer'));


	}

	function entry(Request $request)
	{

		$employer_id = $request->employer_id;	
		
		$m_employer = MEmployerModel::where('employer_id', $employer_id)->first();
        
		if(is_null($m_employer)){
			$m_employer = new MEmployerModel;

			$m_employer->employer_id = 0;

		}

		$employer_categories = MSubKindModel::GetData(1);
		
		return view('Manager.Screen.Master.MEmployer.entry', compact('m_employer',"employer_categories"));


	}

}
