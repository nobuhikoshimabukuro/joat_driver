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
use App\Http\Requests\ManagerSessionConfirmationRequest;

// Request ↑

class MEmployerController extends Controller
{
    function index(Request $request)
	{

        $completion_info = session('completion_info') ?? null;
        session()->forget('completion_info');

		$prefecture_info = MAddressModel::GetPrefectureInfo();
        
		$m_employer = MEmployerModel::withTrashed()->get();
		return view('Manager.Screen.Master.MEmployer.index', compact('m_employer','completion_info'));


	}

	function entry(Request $request)
	{

		$employer_id = $request->employer_id;	
		
		$m_employer = MEmployerModel::withTrashed()->where('employer_id', $employer_id)->first();
        
		if(is_null($m_employer)){
			$m_employer = new MEmployerModel;

			$m_employer->employer_id = 0;

		}

		$employer_categories = MSubKindModel::GetData(1);
		
		return view('Manager.Screen.Master.MEmployer.entry', compact('m_employer',"employer_categories"));


	}

	function save(MEmployerRequest $request)
	{

		$now = now();
        $user_id = session("manager_user_id");

        $message_parts = "更新処理";

        try {
            $table = MEmployerModel::withTrashed()->where('employer_id', $request->employer_id)->first();

            if (empty($table)) {

                if ($request->employer_id != 0) {
                 
                    $result_array = array(
                        "result" => "error",
                        "message" => "文言",
                    );

                    return response()->json(['result_array' => $result_array]);
                }

                $message_parts = "登録処理";

                // 新規のときだけ
                $table = new MEmployerModel;                
                $table->created_by = $user_id;
                $table->created_at = $now;
            }

            $table->employer_category = $request->employer_category;
            $table->corporate_number = $request->corporate_number;
            $table->employer_cd = $request->employer_cd;
            $table->employer_name = $request->employer_name;
            $table->employer_name_kana = $request->employer_name_kana;
            $table->postal_code = $request->postal_code;
            $table->address1 = $request->address1;
			$table->address2 = $request->address2;
			$table->address3 = $request->address3;
            $table->tel1 = $request->tel1;
			$table->tel2 = $request->tel2;
			$table->fax1 = $request->fax1;
			$table->fax2 = $request->fax2;
			$table->mailaddress = $request->mailaddress;
            $table->remarks = $request->remarks;
            $table->updated_by = $user_id;
            $table->updated_at = $now;

            // テーブル更新
            $table->save();

			$employer_id = $table->employer_id;

            $completion_info = [
                'completion_flg'=> true
                ,'target_row_id'=> $employer_id
                ,'message'=> "求人元ID【{$employer_id}】の{$message_parts}が完了しました。"

            ];
			

            session()->put('completion_info', $completion_info);        

            $result_array = array(
                "result" => "success",
                "message" => "",
				"url" => route('manager.master.m_employer'),
            );
     
        } catch (Exception $e) {
            $error_message = $e->getMessage();
            
            Log::channel('error_log')->info("【employer_save】{$error_message}");

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
            $table = MEmployerModel::withTrashed()->where('employer_id', $request->employer_id)->first();

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

            $employer_id = $table->employer_id;

            $completion_info = [
                'completion_flg'=> true
                ,'target_row_id'=> $employer_id
                ,'message'=> "求人元ID【{$employer_id}】の{$message_parts}が完了しました。"

            ];
			

            session()->put('completion_info', $completion_info);        

            $result_array = array(
                "result" => "success",
                "message" => "",
				"url" => route('manager.master.m_employer'),
            );

        } catch (Exception $e) {
            $error_message = $e->getMessage();
            
            Log::channel('error_log')->info("【employer_delete】{$error_message}");
            $result_array = [
                "result" => "error",
                "message" => "{$message_parts}でエラーが発生しました[{$error_message}]"
            ];
        }

        return response()->json(['result_array' => $result_array]);
	}

}
