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
use App\Http\Requests\ManagerSessionConfirmationRequest;


// Request ↑

class MLicenseController extends Controller
{
    function index(Request $request)
	{
        $completion_info = session('completion_info') ?? null;
        session()->forget('completion_info');


		$m_license = MLicenseMmodel::withTrashed()->get();
        
		
		return view('Manager.Screen.Master.MLicense.index', compact('m_license','completion_info'));
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
        $user_id = session("manager_user_id");
        
        $message_parts = "更新処理";

        try {
            $table = MLicenseMmodel::withTrashed()->where('license_id', $request->license_id)->first();

            if (empty($table)) {

                if ($request->license_id != 0) {
                 
                    $result_array = array(
                        "result" => "error",
                        "message" => "文言",
                    );

                    return response()->json(['result_array' => $result_array]);
                }

                $message_parts = "登録処理";

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
            $completion_info = [
                'completion_flg'=> true
                ,'target_row_id'=> $license_id
                ,'message'=> "資格・免許ID【{$license_id}】の{$message_parts}が完了しました。"

            ];
			

            session()->put('completion_info', $completion_info);

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

    function delete(ManagerSessionConfirmationRequest $request)
	{

		$now = now();
        $user_id = session("manager_user_id");
	    // $process = 1 が削除、$process = 2 が削除から復活;
        $process = $request->process;

        try {
            $table = MLicenseMmodel::withTrashed()->where('license_id', $request->license_id)->first();

            if (empty($table)) {
				$result_array = [
					"result" => "error",
					"message" => "指定されたデータが存在しません。",
				];
				return response()->json(['result_array' => $result_array]);
			}

            if ($process == 1) {
				// 論理削除の処理
                $message_parts = "削除処理";
				$table->deleted_by = $user_id;
				$table->deleted_at = $now;
			} else {
                $message_parts = "削除の取消処理";
				$table->deleted_by = null;
				$table->deleted_at = null;
			}

			// 変更を保存
			$table->save();

            $license_id = $table->license_id;

            $completion_info = [
                'completion_flg'=> true
                ,'target_row_id'=> $license_id
                ,'message'=> "資格・免許ID【{$license_id}】の{$message_parts}が完了しました。"

            ];
			

            session()->put('completion_info', $completion_info);        

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
