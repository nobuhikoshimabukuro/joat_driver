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

	function save(MEmployerRequest $request)
	{

		$now = now();
        $user_id = session("user_id");
        $user_id = 1;

        try {
            $table = MEmployerModel::where('employer_id', $request->employer_id)->first();

            if (empty($table)) {

                if ($request->employer_id != 0) {
                 
                    $result_array = array(
                        "result" => "error",
                        "message" => "文言",
                    );

                    return response()->json(['result_array' => $result_array]);
                }

                // 新規のときだけ
                $table = new MEmployerModel;                
                $table->created_by = $user_id;
                $table->created_at = $now;
            }

            $table->employer_category = $request->employer_category;
            $table->corporate_number = $request->corporate_number;
            // $table->employer_cd = $request->employer_cd;
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

            $password_change_flg = $request->password_change_flg ?? "";

            // if ($password_change_flg != "") {
            //     // 保存後の driver_id を取得                
            //     $driver_id = $table->driver_id; // プライマリキーが 'driver_id' の場合

            //     //ドライバーの最新のパスワードindexを取得する
            //     $password_index = m_driver_password_model::get_password_index($driver_id) + 1;

            //     $table = new m_driver_password_model;
            //     $table->driver_id = $driver_id;
            //     $table->password_index = $password_index;
            //     $table->password = $request->password;
            //     $table->created_by = $user_id;
            //     $table->created_at = $now;
            //     $table->updated_by = $user_id;
            //     $table->updated_at = $now;

            //     // テーブル更新
            //     $table->save();

            //     if ($password_index > 1) {
            //         $table = m_driver_password_model::where('driver_id', $driver_id)
            //             ->where('password_index', ($password_index - 1))
            //             ->first();

            //         $table->deleted_by = $user_id;
            //         $table->deleted_at = now();

            //         // テーブル更新
            //         $table->save();
            //     }
            // }

            $result_array = array(
                "result" => "success",
                "message" => "",
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
