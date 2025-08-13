<?php

namespace App\Http\Controllers\Manager\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


// controller作成時ここからコピー↓
use Exception;
use Illuminate\Support\Facades\DB;
use App\Original\DbCommon;
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
use App\Http\Requests\MAddressRequest;
use App\Http\Requests\ManagerSessionConfirmationRequest;

// Request ↑

class MAddressController extends Controller
{
    function index(Request $request)
	{
        $completion_info = session('completion_info') ?? null;
        session()->forget('completion_info');


		// $m_address = MAddressModel::get();
        $m_address = null;
        
		
		return view('Manager.Screen.Master.MAddress.index', compact('m_address','completion_info'));
	}

    function save(MAddressRequest $request)
	{     

        try {
              
            // アップロードされたCSVファイル
            $normal_csv = $request->file('normal_csv');    
            $jigyosyo_csv = $request->file('jigyosyo_csv');
            
            $normal_csv_name = $normal_csv->getClientOriginalName();    
            $jigyosyo_csv_name = $jigyosyo_csv->getClientOriginalName();    

            
            $disk = Storage::disk('AddressCsvPath'); 

            // 既存のディレクトリがなければ作成
            if (!$disk->exists("")) {
                // 新しいディレクトリを作成
                $disk->makeDirectory("");
            }

            // storage/app/public/address_data/ に保存            
            $normal_csv_path = $disk->putFileAs("", $normal_csv, $normal_csv_name);
            $jigyosyo_csv_path = $disk->putFileAs("", $jigyosyo_csv, $jigyosyo_csv_name);
    
            // ファイルの保存確認
            if ((!$normal_csv_path) || (!$jigyosyo_csv_path) )  {

                return response()->json([
                    "result_array" => [
                        "result" => "error",
                        "message" => "ファイルの保存に失敗しました"
                    ]
                ]);
            }
    
            $csvFilenames = (object)[
                'normalCsvName' => $normal_csv_name,
                'jigyosyoCsvName' => $jigyosyo_csv_name,
              ];

            $result = DbCommon::SaveMAddress($csvFilenames);

    
            if ($result) {

                $result_array = [
                    "result" => "success",
                    "message" => ""
                ];     

                
            } else {

                $result_array = [
                    "result" => "error",
                    "message" => "住所登録処理でエラーが発生しました"
                ];     
                
            }


            $result_array = array(
                "result" => "success",
                "message" => "",
				"url" => route('manager.master.m_address'),
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

}
