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

use App\Models\MLicenseMmodel;
// Model ↑

// Request ↓
use App\Http\Requests\MLicenseRequest;
// Request ↑

class MLicenseController extends Controller
{
    function index(Request $request)
	{


		$m_license = MLicenseMmodel::get();
        
		$demo = "";
		return view('Manager.Screen.Master.MLicense.index', compact('m_license'));


	}

    function entry(Request $request)
	{

		$license_id = $request->license_id;	
		
		$m_license = MLicenseMmodel::where('license_id', $license_id)->first();
        
		if(is_null($m_license)){
			$m_license = new MLicenseMmodel;

			$m_license->license_id = 0;

		}	
		
		return view('Manager.Screen.Master.MLicense.entry', compact('m_license'));
	}

	function save(MLicenseRequest $request)
	{

		$now = now();
        $user_id = session("user_id");
        $user_id = 1;

        try {
            $table = MLicenseMmodel::where('license_id', $request->license_id)->first();

            if (empty($table)) {

                if ($request->license_id != 0) {
                 
                    $result_array = array(
                        "result" => "error",
                        "message" => "文言",
                    );

                    return response()->json(['result_array' => $result_array]);
                }

                // 新規のときだけ
                $table = new MLicenseMmodel;                
                $table->created_by = $user_id;
                $table->created_at = $now;
            }
            
            $table->license_name = $request->license_name;
            $table->license_name_kana = $request->license_name_kana;
			$table->display_order = $request->display_order;
            $table->remarks = $request->remarks;
            $table->updated_by = $user_id;
            $table->updated_at = $now;

            // テーブル更新
            $table->save();

			$license_id = $table->license_id;

			session()->flash('success', '登録が完了しました。');
			session()->flash('target_row_id', $license_id);
            $result_array = array(
                "result" => "success",
                "message" => "",
				"url" => route('manager.master.m_license'),
            );

        } catch (Exception $e) {
            $error_message = $e->getMessage();
            

            $result_array = [
                "result" => "error",
                "message" => "登録処理でエラーが発生しました[{$error_message}]"
            ];
        }

        return response()->json(['result_array' => $result_array]);


	}

}
