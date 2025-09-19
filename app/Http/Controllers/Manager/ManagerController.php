<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



// controller作成時ここからコピー↓
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Original\db_common;
use App\Original\Manager\ManagerCommon;



use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\Snappy\Facades\SnappyPdf as SnappyPDF;
use League\Csv\Reader;
use Illuminate\Support\Facades\File;

use PhpOffice\PhpSpreadsheet\IOFactory;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Picqer\Barcode\BarcodeGeneratorPNG;
// controller作成時ここまでコピー↑

// Model ↓
use App\Models\MManagerUserModel;

// Model ↑

// Request ↓

// Request ↑



class ManagerController extends Controller
{

    function test(Request $request)
	{		
        $demo = "";
        return view('Manager.Screen.test', compact('demo'));        
	}


    function excel_upload(Request $request)
	{		

        
        if ($request->hasFile('excel')) {
            $file = $request->file('excel');
            $filename = $file->getClientOriginalName();
    
             // PhpSpreadsheetで読み込み
            $spreadsheet = IOFactory::load($file->getPathname());

            // アクティブシート取得
            $sheet = $spreadsheet->getActiveSheet();

            // 読み込むセル情報を定義
            $cells = self::cell_info();

            
            // return_array をオブジェクトにする
            $return_array = new \stdClass();
            foreach ($cells as $item) {
                $return_array->{$item->name} = $sheet->getCell($item->cell)->getValue() ?? "";
            }

            // JSON返却用
            $result_array = [
                "result" => "success",
                "message" => "アップロード完了: " . $filename,
                "return_array" => $return_array,
            ];

        } else {
            $result_array = [
                "result" => "error",
                "message" => "ファイルが選択されていません",
            ];
        }



      
        return response()->json(['result_array' => $result_array]);
	}


    function excel_download(Request $request)
	{		

        // 読み込むセル情報を定義
        $cells = self::cell_info();

        $outputExcelFileName = "test.xlsx";
        $relativeOutputDir = "excel/output";

        $templatePath = storage_path("app/excel/template/test.xlsx");
        $outputExcelPath = storage_path("app/{$relativeOutputDir}/{$outputExcelFileName}");

        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getSheetByName('main');

        
        foreach ($cells as $item) {

            $name = $item->name;

            $sheet->setCellValue($item->cell, $request->$name);            
        }

        //excelの保存
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($outputExcelPath);


	}


    function cell_info()
	{		
        $cells = [
            (object)["name" => "job_title", "cell" => "A1"],
            (object)["name" => "job_type",  "cell" => "A2"],
        ];
        
        return $cells;
	}


    function index(Request $request)
	{		
		// セッション情報取得
        $session_info = ManagerCommon::GetManagerUserInfo();
        // セッション有無
        if ($session_info->login_status) {       

            return redirect(session('manager_after_login_url', route('manager.dashboard')));

        }else{
            return redirect(route('manager.login'));
        }    		
        
	}

	function login(Request $request){

        // セッション情報取得
        $session_info = ManagerCommon::GetManagerUserInfo();
        // セッション有無
        if ($session_info->login_status) {            
            return redirect(route('manager.dashboard'));
        }        
        
        $demo = "";
		return view('Manager.Screen.login', compact('demo'));       
     
    }

    function login_check(Request $request){

        $errors = [];
        $not_entered = [];
        // ★ 共通返却ロジックをクロージャとして定義
		$return_result = function($errors) {
		
            $errors = (object)$errors;
            session()->flash('errors',$errors);
            return redirect(route('manager.login'))->withInput();            
		};
       
        $user_cd = $request->user_cd;
        $password = $request->password;

        if(trim($user_cd) == "" || is_null($user_cd)){      
            $errors["user_cd"]= 1;
            $not_entered[] = "ユーザーコード";            
        }

        if(trim($password) == "" || is_null($password)){ 
            $errors["password"]= 1;         
            $not_entered[] = "パスワード";            
        }
        
        if (!empty($not_entered)) {            
            $errors["login_error_message"]= implode('、', $not_entered) . 'は必須項目です。';
            return $return_result($errors);
        }
        
        //ユーザーCDとパスワードを
        $m_manager_user = MManagerUserModel::where('user_cd', $user_cd)->where('password', $password)->first();

        if(is_null($m_manager_user)){
            $errors["login_error_message"]= "ユーザーCD、またはパスワードが一致しません";
            return $return_result($errors);            
        }

        $manager_user_id = $m_manager_user->user_id;
        
        session()->put(['manager_user_id' => $manager_user_id]);              
        session()->save();
        
        if (session()->has('manager_after_login_url')) {

            $manager_after_login_url = session('manager_after_login_url');
            session()->forget('manager_after_login_url');
            return redirect($manager_after_login_url);

        }else{
            return redirect(route('manager.dashboard'));  
        }
    }

    function logout(Request $request)
	{        
        // セッション破棄
        ManagerCommon::DestroyManagerUserSession();
        return redirect(route('manager.index'));	
	}

    function dashboard(Request $request)
	{        
        
        $demo = "";
		return view('Manager.Screen.dashboard', compact('demo'));
	
	}
}
