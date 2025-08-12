<?php

namespace App\Original\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


// controller作成時ここからコピー↓
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Original\db_common;



use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\Snappy\Facades\SnappyPdf as SnappyPDF;
use League\Csv\Reader;
use Illuminate\Support\Facades\File;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Picqer\Barcode\BarcodeGeneratorPNG;
// controller作成時ここまでコピー↑

// Model ↓
use App\Models\MManagerUserModel;
// Model ↑

// Request ↓

// Request ↑

class ManagerCommon
{

	public static function GetManagerUserInfo()
	{
		$login_status = false;
		$manager_user_id = 0;

		//session内にmanager_user_idの存在確認
		if (session()->has('manager_user_id')) {

			$manager_user_id = session('manager_user_id');

			$m_manager_user = MManagerUserModel::where('user_id', $manager_user_id)->first();

			//manager_user_idでユーザー情報取得確認
			if (!is_null($m_manager_user)) {

				self::DestroyManagerUserSession();

				session()->put(['manager_user_id' => $m_manager_user->user_id]);

				session()->put(['manager_user_name' => "{$m_manager_user->last_name} {$m_manager_user->first_name}"]);
				session()->put(['manager_user_last_name' => $m_manager_user->last_name]);
				session()->put(['manager_user_first_name' => $m_manager_user->first_name]);

				session()->put(['manager_user_name_kana' => "{$m_manager_user->last_name_kana} {$m_manager_user->first_name_kana}"]);
				session()->put(['manager_user_last_name_kana' => $m_manager_user->last_name_kana]);
				session()->put(['manager_user_first_name_kana' => $m_manager_user->first_name_kana]);
				
				
				session()->save();
				$login_status = true;				
			}
		}

		$session_info = (object)[
			"login_status" => $login_status,
			"manager_user_id" => $manager_user_id
		];

		return $session_info;
	}

	public static function DestroyManagerUserSession()
	{

		session()->forget(
			[
				'manager_user_id',
				'manager_user_last_name',
				'manager_user_first_name',
				'manager_user_last_name_kana',
				'manager_user_first_name_kana',				
			]
		);


	}
	public static function test()
	{
		

		return "";
	}

}
