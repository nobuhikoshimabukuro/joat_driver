<?php

namespace App\Original;

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
use App\Models\m_user_model;
use App\Models\m_driver_model;
use App\Models\t_pickup_request_model;
use App\Models\t_company_permission_model;
use App\Models\m_japanese_era_model;
use App\Models\m_address_model;
use App\Models\t_application_version_model;
// Model ↑

// Request ↓

// Request ↑

class Common
{



	public static function get_user_session_info()
	{
		$login_status = false;
		$user_id = 0;

		//session内にuser_idの存在確認
		if (session()->has('user_id')) {

			$user_id = session('user_id');

			$m_user = m_user_model::select(
				'm_user.user_id',
				'm_user.company_id',
				'm_company.company_name',
				'm_company.company_name_kana',
				'm_user.user_cd',
				'm_user.last_name',
				'm_user.first_name',
				'm_user.last_name_kana',
				'm_user.first_name_kana',
				'm_user.permission AS user_permission',
			)
				->where('m_user.user_id', $user_id)
				->leftJoin('m_company', 'm_user.company_id', '=', 'm_company.company_id')
				->first();

			//user_idでユーザー情報取得確認
			if (!is_null($m_user)) {

				self::destroy_user_session();

				session()->put(['user_id' => $m_user->user_id]);
				session()->put(['company_id' => $m_user->company_id]);
				session()->put(['company_name' => $m_user->company_name]);
				session()->put(['company_name_kana' => $m_user->company_name_kana]);
				session()->put(['user_cd' => $m_user->user_cd]);
				session()->put(['user_last_name' => $m_user->last_name]);
				session()->put(['user_first_name' => $m_user->first_name]);
				session()->put(['user_last_name_kana' => $m_user->last_name_kana]);
				session()->put(['user_first_name_kana' => $m_user->first_name_kana]);
				session()->put(['user_permission' => $m_user->user_permission]);
				session()->save();
				$login_status = true;


				self::set_user_menu();
			}
		}else{
			$user_menu_info[] = (object)
				[
					"href" => route('user.logout'),
					"icon" => "",
					"title" => "ログアウト",
					"menu_flg" => 0,
					"header_flg" => 1,
					"sort_order" => 999
				];
			session()->put(['user_menu_info' => $user_menu_info]);
		}

		$session_info = (object)[
			"login_status" => $login_status,
			"user_id" => $user_id
		];

		return $session_info;
	}

	public static function get_driver_session_info()
	{
		$login_status = false;
		$driver_id = 0;

		//session内にdriver_idの存在確認
		if (session()->has('driver_id')) {

			$driver_id = session('driver_id');

			$m_driver = m_driver_model::select(
				'm_driver.driver_id',
				'm_driver.driver_cd',
				'm_driver.last_name',
				'm_driver.first_name',
				'm_driver.last_name_kana',
				'm_driver.first_name_kana',
			)
				->where('m_driver.driver_id', $driver_id)
				->first();

			//driver_idでユーザー情報取得確認
			if (!is_null($m_driver)) {

				self::destroy_driver_session();

				session()->put(['driver_id' => $m_driver->driver_id]);
				session()->put(['driver_last_name' => $m_driver->last_name]);
				session()->put(['driver_first_name' => $m_driver->first_name]);
				session()->put(['driver_last_name_kana' => $m_driver->last_name_kana]);
				session()->put(['driver_first_name_kana' => $m_driver->first_name_kana]);
				session()->save();
				$login_status = true;

				self::set_driver_menu();

				session()->forget(['pickup_request_count',]);

				$pickup_request_count = self::get_pickup_request_count($driver_id);

				session()->put(['pickup_request_count' => $pickup_request_count]);
				session()->save();
			}
		}

		$session_info = (object)[
			"login_status" => $login_status,
			"driver_id" => $driver_id
		];

		return $session_info;
	}


	public static function destroy_user_session()
	{
		session()->forget(
			[
				'user_id',
				'company_id',
				'company_name',
				'company_name_kana',
				'user_cd',
				'user_last_name',
				'user_first_name',
				'user_last_name_kana',
				'user_first_name_kana',
				'user_permission',
			]
		);
	}

	public static function destroy_driver_session()
	{
		session()->forget(
			[
				'driver_id',
				'driver_last_name',
				'driver_first_name',
				'driver_last_name_kana',
				'driver_first_name_kana',
			]
		);
	}

	public static function set_user_after_login_url(Request $request)
	{
		// 現在のURLを取得            
		$user_after_login_url = $request->fullUrl();
		session()->forget('user_after_login_url');
		session()->put(['user_after_login_url' => $user_after_login_url]);
	}

	public static function set_driver_after_login_url(Request $request)
	{
		// 現在のURLを取得            
		$driver_after_login_url = $request->fullUrl();
		session()->forget('driver_after_login_url');
		session()->put(['driver_after_login_url' => $driver_after_login_url]);
	}

	/**
	 * 1.会社権限から管理会社か判断する
	 * 2.ユーザー権限から管理者か判断する
	 * 3.会社権限とユーザー権限にてメニュー表示を制御する
	 */
	public static function set_user_menu()
	{

		$company_id = session("company_id");
		$user_id = session("user_id");

		$user_permission[] = session("user_permission");
		// 権限 配列に変換したものを取得
		$company_permission = t_company_permission_model::where('company_id', $company_id)
			->pluck('permission') // permission列だけ取り出して
			->toArray();          // 配列に変換       


		$user_menu_info = [];
		$user_master_menu_info = [];
		$user_pricing_menu_info = [];
		$user_billing_menu_info = [];
		$user_data_analysis_menu = [];

		// user_menu_info start

		if($company_id == 1){

			
			$user_menu_info[] = (object)
			[
				"href" => route('development.android_application_upload'),
				"icon" => "",
				"title" => "アプリアップロード",
				"menu_flg" => 0,
				"header_flg" => 1,
				"sort_order" => 100
			];
			
		}

		$user_menu_info[] = (object)
		[
			"href" => route('user.top_menu'),
			"icon" => "",
			"title" => "メニュー",
			"menu_flg" => 0,
			"header_flg" => 1,
			"sort_order" => 1
		];

		$user_menu_info[] = (object)
		[
			"href" => route('user.shipping_label'),
			"icon" => "far fa-file-alt",
			"title" => "データ照会",
			"menu_flg" => 1,
			"header_flg" => 1,
			"sort_order" => 1
		];

		$user_menu_info[] = (object)
		[
			"href" => route('user.shipping_label.entry'),
			"icon" => "far fa-file",
			"title" => "送り状作成",
			"menu_flg" => 1,
			"header_flg" => 1,
			"sort_order" => 2
		];

		// 20250522時点では非表示
		// $user_menu_info[] = (object)
		// [
		// 	"href" => route('user.pickup_request'),
		// 	"icon" => "fas fa-comment-dots",
		// 	"title" => "集荷依頼",
		// 	"menu_flg" => 1,
		// 	"header_flg" => 1,
		// 	"sort_order" => 3
		// ];

		$user_menu_info[] = (object)
		[
			"href" => route('user.master_menu'),
			"icon" => "fas fa-table",
			"title" => "マスタメニュー",
			"menu_flg" => 1,
			"header_flg" => 1,
			"sort_order" => 4
		];



		$check_company_permission = [1];
		$check_user_permission = [1];
		if (
			count(array_intersect($check_company_permission, $company_permission)) > 0 &&
			count(array_intersect($check_user_permission, $user_permission)) > 0
		) {
			$user_menu_info[] = (object)
			[
				"href" => route('user.pricing_menu'),
				"icon" => "fas fa-yen-sign",
				"title" => "料金関連メニュー",
				"menu_flg" => 1,
				"header_flg" => 1,
				"sort_order" => 101
			];

			$user_menu_info[] = (object)
			[
				"href" => route('user.billing_menu'),
				"icon" => "fas fa-table",
				"title" => "請求関連メニュー",
				"menu_flg" => 1,
				"header_flg" => 1,
				"sort_order" => 102
			];

			$user_menu_info[] = (object)
			[
				"href" => route('user.data_analysis_menu'),
				"icon" => "far fa-chart-bar",
				"title" => "データ解析",
				"menu_flg" => 1,
				"header_flg" => 1,
				"sort_order" => 103
			];
		}

		$user_menu_info[] = (object)
		[
			"href" => route('user.logout'),
			"icon" => "",
			"title" => "ログアウト",
			"menu_flg" => 0,
			"header_flg" => 1,
			"sort_order" => 999
		];


		// user_menu_info end


		// user_master_menu_info start



		$check_company_permission = [1];
		$check_user_permission = [1];
		if (
			count(array_intersect($check_company_permission, $company_permission)) > 0 &&
			count(array_intersect($check_user_permission, $user_permission)) > 0
		) {


			$user_master_menu_info[] = (object)
			[
				"href" => route('user.master.m_company'),
				"icon" => "fas fa-network-wired",
				"title" => "会社情報",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 1
			];

			$user_master_menu_info[] = (object)
			[
				"href" => route('user.master.m_driver'),
				"icon" => "fas fa-truck",
				"title" => "ドライバー情報",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 4
			];

			$user_master_menu_info[] = (object)
			[
				"href" => route('user.master.m_address'),
				"icon" => "fas fa-cloud-upload-alt",
				"title" => "住所情報",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 10
			];

			$user_master_menu_info[] = (object)
			[
				"href" => route('user.master.m_branch_office'),
				"icon" => "fas fa-building",
				"title" => "営業所情報",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 11
			];
		}

		$user_master_menu_info[] = (object)
		[
			"href" => route('user.top_menu'),
			"icon" => "fas fa-arrow-left",
			"title" => "メニュー画面へ戻る",
			"menu_flg" => 1,
			"header_flg" => 0,
			"sort_order" => 0
		];

		$user_master_menu_info[] = (object)
		[
			"href" => route('user.master.m_user'),
			"icon" => "far fa-id-card",
			"title" => "ユーザー情報",
			"menu_flg" => 1,
			"header_flg" => 1,
			"sort_order" => 3
		];



		$user_master_menu_info[] = (object)
		[
			"href" => route('user.master.m_client'),
			"icon" => "fas fa-dolly-flatbed",
			"title" => "取引先情報",
			"menu_flg" => 1,
			"header_flg" => 1,
			"sort_order" => 2
		];

		$user_master_menu_info[] = (object)
		[
			"href" => route('user.master.m_remarks'),
			"icon" => "far fa-comment-alt",
			"title" => "備考情報(送り状用)",
			"menu_flg" => 1,
			"header_flg" => 1,
			"sort_order" => 9
		];


		// user_master_menu_info end



		// user_pricing_menu_info start
		$user_pricing_menu_info[] = (object)
		[
			"href" => route('user.top_menu'),
			"icon" => "fas fa-arrow-left",
			"title" => "メニュー画面へ戻る",
			"menu_flg" => 1,
			"header_flg" => 0,
			"sort_order" => 1
		];

		$check_company_permission = [1];
		$check_user_permission = [1];
		if (
			count(array_intersect($check_company_permission, $company_permission)) > 0 &&
			count(array_intersect($check_user_permission, $user_permission)) > 0
		) {


			$user_pricing_menu_info[] = (object)
			[
				"href" => route('user.master.m_price_regular'),
				"icon" => "fas fa-pen",
				"title" => "区域/料金設定（レギュラー）",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 1
			];

			$user_pricing_menu_info[] = (object)
			[
				"href" => route('user.master.m_price_regular_area'),
				"icon" => "fas fa-map-marked-alt",
				"title" => "エリア設定（レギュラー）",
				"menu_flg" => 0,		// メニューから非表示に
				"header_flg" => 0,
				"sort_order" => 2
			];

			$user_pricing_menu_info[] = (object)
			[
				"href" => route('user.master.m_price_island'),
				"icon" => "fas fa-anchor",
				"title" => "料金設定（レギュラー：離島）",
				"menu_flg" => 0,		// メニューから非表示に
				"header_flg" => 0,
				"sort_order" => 3
			];

			$user_pricing_menu_info[] = (object)
			[
				"href" => route('user.master.m_price_custom'),
				"icon" => "fas fa-list-ul",
				"title" => "料金カスタム設定",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 4
			];

			// 2025/04/25 報酬分類画面、報酬設定画面（画面名含めて仮です）
			$user_pricing_menu_info[] = (object)
			[
				"href" => route('user.master.m_reward_settings'),
				"icon" => "	fas fa-address-book",
				"title" => "報酬分類画面",
				"menu_flg" => 0,		// メニューから非表示に
				"header_flg" => 0,
				"sort_order" => 5
			];

			$user_pricing_menu_info[] = (object)
			[
				"href" => route('user.master.driver_reward_rate'),
				"icon" => "fas fa-percent",
				"title" => "報酬設定画面",
				"menu_flg" => 0,		// メニューから非表示に
				"header_flg" => 0,
				"sort_order" => 6
			];

			$user_pricing_menu_info[] = (object)
			[
				"href" => route('user.master.m_charge_category_major'),
				"icon" => "fas fa-window-maximize",
				"title" => "料金大分類マスタ",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 7
			];

			$user_pricing_menu_info[] = (object)
			[
				"href" => route('user.master.m_charge_category_middle'),
				"icon" => "fas fa-window-restore",
				"title" => "料金中分類マスタ",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 8
			];

			$user_pricing_menu_info[] = (object)
			[
				"href" => route('user.master.m_charge_category_minor'),
				"icon" => "far fa-window-restore",
				"title" => "料金小分類マスタ",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 9
			];

			$user_pricing_menu_info[] = (object)
			[
				"href" => route('user.master.m_price_setting.index'),
				"icon" => "fas fa-hand-holding-usd",
				"title" => "分類別料金設定",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 10
			];

			$user_pricing_menu_info[] = (object)
			[
				"href" => route('user.master.m_internal_service_fee'),
				"icon" => "far fa-window-restore",
				"title" => "館内手数料情報",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 11
			];



			$user_pricing_menu_info[] = (object)
			[
				"href" => route('user.price_check'),
				"icon" => "fas fa-search-dollar",
				"title" => "料金検証",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 99
			];
		}



		// user_pricing_menu_info end


		// user_data_analysis_menu start

		$user_data_analysis_menu[] = (object)
		[
			"href" => route('user.top_menu'),
			"icon" => "fas fa-arrow-left",
			"title" => "メニュー画面へ戻る",
			"menu_flg" => 1,
			"header_flg" => 0,
			"sort_order" => 1
		];

		$check_company_permission = [1];
		$check_user_permission = [1];
		if (
			count(array_intersect($check_company_permission, $company_permission)) > 0 &&
			count(array_intersect($check_user_permission, $user_permission)) > 0
		) {

			$user_data_analysis_menu[] = (object)
			[
				"href" => route('user.data_analysis.condition_settings'),
				"icon" => "fas fa-pen",
				"title" => "条件設定",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 2
			];
		}


		// user_data_analysis_menu end


		// user_billing_menu_info start

		$user_billing_menu_info[] = (object)
		[
			"href" => route('user.top_menu'),
			"icon" => "fas fa-arrow-left",
			"title" => "メニュー画面へ戻る",
			"menu_flg" => 1,
			"header_flg" => 0,
			"sort_order" => 1
		];


		$check_company_permission = [1];
		$check_user_permission = [1];
		if (
			count(array_intersect($check_company_permission, $company_permission)) > 0 &&
			count(array_intersect($check_user_permission, $user_permission)) > 0
		) {

			$user_billing_menu_info[] = (object)
			[
				"href" => route('user.billing.billing_cd_setting'),
				"icon" => "fas fa-clipboard-list",
				"title" => "請求コード設定",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 1
			];
			$user_billing_menu_info[] = (object)
			[
				"href" =>  route('user.billing.edit_list'),
				"icon" => "fas fa-pen",
				"title" => "請求明細編集",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 1
			];
			$user_billing_menu_info[] = (object)
			[
				"href" =>  route('user.billing'),
				"icon" => "fas fa-file-invoice-dollar",
				"title" => "請求書発行",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 1
			];	
			$user_billing_menu_info[] = (object)
			[
				"href" =>  route('user.billing.from_driver'),
				"icon" => "fas fa-file-alt",
				"title" => "請求書発行（ドライバー用）",
				"menu_flg" => 1,
				"header_flg" => 0,
				"sort_order" => 1
			];
		
		}




		// user_billing_menu_info end
		session()->forget(
			[
				'user_menu_info',
				'user_master_menu_info',
				'user_pricing_menu_info',
				'user_billing_menu_info',
				'user_data_analysis_menu',

				'company_permission',
				'user_permission',
			]
		);

		session()->put(['user_menu_info' => $user_menu_info]);
		session()->put(['user_master_menu_info' => $user_master_menu_info]);
		session()->put(['user_billing_menu_info' => $user_billing_menu_info]);
		session()->put(['user_pricing_menu_info' => $user_pricing_menu_info]);
		session()->put(['user_data_analysis_menu' => $user_data_analysis_menu]);

		session()->put(['user_permission' => $user_permission]);
		session()->put(['company_permission' => $company_permission]);

		session()->save();
	}

	public static function set_driver_menu()
	{
		$driver_menu_info = [];
		$driver_menu_info[] = (object)
		["href" => route('driver.menu'), "icon" => "", "title" => "メニュー", "menu_flg" => 0, "header_flg" => 1, "sort_order" => 1];

		$driver_menu_info[] =
			(object)["href" => route('driver.shipment_data'), "icon" => "far fa-file-alt", "title" => "配送データ一覧", "menu_flg" => 1, "header_flg" => 1, "sort_order" => 1];

		$driver_menu_info[] =
			(object)["href" => route('driver.pickup_request_check'), "icon" => "fas fa-comment-dots", "title" => "集荷依頼確認", "menu_flg" => 1, "header_flg" => 1, "sort_order" => 1];

		$driver_menu_info[] =
			(object)["href" => route('driver.driver_data'), "icon" => "fas fa-truck", "title" => "ドライバー情報", "menu_flg" => 1, "header_flg" => 1, "sort_order" => 1];

		// 2024/04/23 追加
		$driver_menu_info[] =
			(object)["href" => route('driver.daily_report'), "icon" => "fas fa-book", "title" => "日報", "menu_flg" => 1, "header_flg" => 1, "sort_order" => 1];

		$driver_menu_info[] =
			(object)["href" => route('driver.logout'), "icon" => "", "title" => "ログアウト", "menu_flg" => 0, "header_flg" => 1, "sort_order" => 1];

		session()->forget(
			[
				'driver_menu_info',
			]
		);
		session()->put(['driver_menu_info' => $driver_menu_info]);
		session()->save();
	}

	// ドライバー 集荷依頼カウント
	public static function get_pickup_request_count($driver_id)
	{


		$pickup_request_count = t_pickup_request_model::where('driver_id', $driver_id)
			->where('read_flg', 0)  // TODO 現状 未読状態のものを対象としておく
			->count();



		return $pickup_request_count;
	}



	public static function cryptographic_slip_number($slip_number)
	{
		$return_value = 0;

		// 文字列を1文字ずつ分割
		$chars = str_split($slip_number);

		$set_number = "";
		// 数字のみを抽出して配列に追加
		foreach ($chars as $char) {
			if (is_numeric($char)) {
				$set_number .= $char;
			}
		}

		// 数字がない場合は 7 にする
		$numeric_value = ($set_number == "") ? 7 : (int)$set_number;

		$return_value = ($numeric_value + $numeric_value + $numeric_value);

		return $return_value;
	}


	public static function get_union_f_shipment_sql()
	{

		$sql = "
        WITH base_data AS ( 
            SELECT
                slip_number
                , company_id
                , shipment_date
                , shipper_post_code
                , shipper_address1
                , shipper_address2
                , shipper_address3
                , shipper_name1
                , shipper_name2
                , shipper_name3
                , shipper_tel
                , consignee_post_code
                , consignee_address1
                , consignee_address2
                , consignee_address3
                , consignee_name1
                , consignee_name2
                , consignee_name3
                , consignee_tel
                , remarks
                , 1 AS data_flg
                , created_by                            -- 2025/04/28 追加
            FROM
                f_shipment_data 
            UNION 
            SELECT
                slip_number
                , company_id
                , shipment_date
                , shipper_post_code
                , shipper_address1
                , shipper_address2
                , shipper_address3
                , shipper_name1
                , shipper_name2
                , shipper_name3
                , shipper_tel
                , consignee_post_code
                , consignee_address1
                , consignee_address2
                , consignee_address3
                , consignee_name1
                , consignee_name2
                , consignee_name3
                , consignee_tel
                , CONCAT(remarks1, ' ', remarks2) AS remarks
                , 2 AS data_flg
                , created_by                            -- 2025/04/28 追加
            FROM
                f_shipment 
            WHERE
                slip_number NOT IN (SELECT slip_number FROM f_shipment_data)
        ) 
        , w_f_delivery_status AS ( 
            SELECT
                slip_number
                , current_status
                , remarks 
            FROM
                ( 
                    SELECT
                        f_delivery_status.slip_number
                        , f_delivery_status.current_status
                        , f_delivery_status.remarks 
                    FROM
                        ( 
                            SELECT
                                slip_number
                                , MAX(current_status) as current_status 
                            FROM
                                f_delivery_status 
                            GROUP BY
                                slip_number
                        ) max_delivery_status 
                        LEFT JOIN f_delivery_status 
                            ON max_delivery_status.slip_number = f_delivery_status.slip_number 
                            AND max_delivery_status.current_status = f_delivery_status.current_status
                ) Work 
            GROUP BY
                slip_number
                , current_status
                , remarks
        ) 
        SELECT
            m_company.company_name
            , base_data.slip_number
            , base_data.company_id
            , base_data.shipment_date
            , base_data.shipper_post_code
            , base_data.shipper_address1
            , base_data.shipper_address2
            , base_data.shipper_address3
            , base_data.shipper_name1
            , base_data.shipper_name2
            , base_data.shipper_name3
            , base_data.shipper_tel
            , base_data.consignee_post_code
            , base_data.consignee_address1
            , base_data.consignee_address2
            , base_data.consignee_address3
            , base_data.consignee_name1
            , base_data.consignee_name2
            , base_data.consignee_name3
            , base_data.consignee_tel
            , base_data.remarks
            , base_data.data_flg
            , w_f_delivery_status.current_status
            , w_f_delivery_status.remarks AS 'delivery_status_remarks'
            , base_data.created_by                      -- 2025/04/28 追加
        FROM
            base_data 
            LEFT JOIN w_f_delivery_status 
                ON base_data.slip_number = w_f_delivery_status.slip_number 
            LEFT JOIN m_company 
                ON base_data.company_id = m_company.company_id
        
        ";

		return $sql;
	}


	public static function get_address_info_sql($process_branch = 1)
	{

		$sql = "

            WITH latest_data AS ( 
                SELECT
                    postal_code
                    , MAX(id) AS max_id 
                FROM
                    m_address 
                GROUP BY
                    postal_code
            ) 
            , base_data AS ( 
                SELECT
                    LEFT (municipality_code, 2) AS prefecture_cd
                    , m_address.* 
                FROM
                    m_address 
                    INNER JOIN latest_data 
                        ON latest_data.postal_code = m_address.postal_code 
                        AND latest_data.max_id = m_address.id
            ) 
            , prefecture_data AS ( 
                SELECT
                    LEFT (municipality_code, 2) AS prefecture_cd
                    , prefecture 
                FROM
                    base_data 
                GROUP BY
                    LEFT (municipality_code, 2)
                    , prefecture
            ) 
            , municipality_data AS ( 
                SELECT
                    LEFT (municipality_code, 2) AS prefecture_cd
                    , municipality_code
                    , postal_code
                    , prefecture
                    , city                     
                FROM
                    base_data 
                GROUP BY
                    municipality_code
                    , postal_code
                    , prefecture
                    , city
            ) 
        
        ";

		switch ($process_branch) {
			case 1:
				$sql .= "

                    SELECT
                    *
                    FROM
                    base_data
                
                
                ";
				break;
			case 2:

				$sql .= "

                    SELECT
                    *
                    FROM
                    prefecture_data
                    
                
                ";

				break;
			case 3:

				$sql .= "

                    SELECT
                    *
                    FROM
                    municipality_data                
                ";

			default:
				// どのケースにも一致しない場合の処理
				break;
		}

		return $sql;
	}


	public static function generateRandomString($length = 10)
	{

		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[random_int(0, $charactersLength - 1)];
		}
		return $randomString;
	}



	public static function get_upload_file_infos($slip_number)
	{
		$file_path = "{$slip_number}/slip"; // ファイルが格納されるディレクトリ
		$files = Storage::disk('upload_save_path')->files($file_path);

		$file_name = count($files) > 0 ? basename($files[0]) : null; // 最初のファイルを取得        
		$file_url = $file_name ? Storage::disk('upload_save_path')->url("{$file_path}/{$file_name}") : null;
		$file_url = $file_url ? asset($file_url) : null;

		$slip_file_info = null;
		if ($file_url) {
			$extension = pathinfo($file_name, PATHINFO_EXTENSION); // 拡張子を取得
			$slip_file_info = (object)["file_url" => $file_url, "extension" => $extension];
		}

		$index = 1;
		$capture_file_infos = null;
		while (true) {

			$file_path = "{$slip_number}/capture{$index}"; // ファイルが格納されるディレクトリ



			$files = Storage::disk('upload_save_path')->files($file_path);
			if (count($files) == 0) {
				break;
			}

			$file_name = count($files) > 0 ? basename($files[0]) : null; // 最初のファイルを取得        
			$file_url = $file_name ? Storage::disk('upload_save_path')->url("{$file_path}/{$file_name}") : null;
			$file_url = $file_url ? asset($file_url) : null;

			if ($file_url) {
				$extension = pathinfo($file_name, PATHINFO_EXTENSION); // 拡張子を取得                
				$capture_file_infos[] = (object)["file_url" => $file_url, "extension" => $extension];
			}
			$index += 1;
		}

		$file_path = "{$slip_number}/sign"; // ファイルが格納されるディレクトリ
		$files = Storage::disk('upload_save_path')->files($file_path);

		$file_name = count($files) > 0 ? basename($files[0]) : null; // 最初のファイルを取得        
		$file_url = $file_name ? Storage::disk('upload_save_path')->url("{$file_path}/{$file_name}") : null;
		$file_url = $file_url ? asset($file_url) : null;

		$sign_file_info = null;
		if ($file_url) {
			$extension = pathinfo($file_name, PATHINFO_EXTENSION); // 拡張子を取得
			$sign_file_info = (object)["file_url" => $file_url, "extension" => $extension];
		}

		return (object)["slip_file_info" => $slip_file_info, "capture_file_infos" => $capture_file_infos, "sign_file_info" => $sign_file_info];
	}


	public static function create_message($message, $add_message)
	{

		if (empty($add_message)) {
			return $message;
		}

		if (!empty($message)) {
			$message .= "\n";
		}
		$message .= $add_message;

		return $message;
	}


	//※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※
	//※本番稼働後は暗号化キーは絶対に変更してはダメ
	//※$encryption_key = 'RTS';
	//※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※
	// 平文から暗号文
	public static function encryption($plain_text)
	{
		$encryption_key = self::get_encryption_key();

		$encrypted_text = openssl_encrypt($plain_text, 'AES-128-ECB', $encryption_key);

		return $encrypted_text;
	}

	// 暗号文から平文
	public static function decryption($encrypted_text)
	{
		$encryption_key = self::get_encryption_key();

		$plain_text = openssl_decrypt($encrypted_text, 'AES-128-ECB', $encryption_key);

		return $plain_text;
	}

	//暗号キー取得処理
	public static function get_encryption_key()
	{
		//※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※
		//※本番稼働後は暗号化キーは絶対に変更してはダメ
		//※$encryption_key = 'RTS';
		//※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※
		$encryption_key = 'RTS';

		return $encryption_key;
	}


	public static function change_japanese_era($target_date)
    {

		try{

			// 日付を Carbon インスタンスに変換
			$date = Carbon::parse($target_date);
			
		} catch (Exception $e) {

			try{

				$date = self::validate_and_parse_date($target_date);
				if (!$date) {
					return null; // 無効な日付なら null を返す
				}	

				$date = Carbon::parse($date);

			} catch (Exception $e) {

				return null; // 無効な日付なら null を返す

			}
			
		}
		

        // 和暦データを取得（target_date が start_date 以上、end_date 未満 or NULL）
        $era_data = m_japanese_era_model::where('start_date', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->where('end_date', '>', $date)
                    ->orWhereNull('end_date');
            })
            ->first();

        // 該当する和暦がない場合
        if (!$era_data) {
            return null;
        }

        // 和暦名
        $era = $era_data->era;

        // 和暦年を計算 (元年の場合は「1」)
        $japanese_year = $date->year - Carbon::parse($era_data->start_date)->year + 1;
        $japanese_year = ($japanese_year == 1) ? '元' : str_pad($japanese_year, 2, '0', STR_PAD_LEFT);

        // 和暦フォーマット（例: 令和 05年 04月 01日）
        $japanese_date = sprintf(
            "%s%s年%02d月%02d日",
            $era,
            $japanese_year,
            $date->month,
            $date->day
        );

        return $japanese_date;
    }

	private static function validate_and_parse_date($date_str)
    {
        try {
            // yyyy-mm-dd, yyyy/mm/dd, yyyymmdd に対応
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_str) || 
                preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $date_str)) {
                return Carbon::createFromFormat('Y-m-d', str_replace('/', '-', $date_str));
            } elseif (preg_match('/^\d{8}$/', $date_str)) {
                return Carbon::createFromFormat('Ymd', $date_str);
            } else {
                return null; // 無効なフォーマット
            }
        } catch (Exception $e) {
            return null; // 変換に失敗した場合
        }
    }

	public static function search_post_code_for_address(Request $request)
	{
		$post_code = "";
	
		try {
			
			$address = $request->address;			
	
			$post_code = m_address_model::get_post_number_for_address($address);
	
		} catch (Exception $e) {

			return response()->json(['status' => 'error', 'message' => '変換失敗'], 500);
		}
	
		return response()->json(['status' => 'success', 'post_code' => $post_code]);
	}

	public static function search_address_for_post_code(Request $request)
	{
		$address = "";
	
		try {

			$post_code = $request->post_code;			
	
			$address = m_address_model::get_address_for_post_number($post_code);
	
		} catch (Exception $e) {
			
			return response()->json(['status' => 'error', 'message' => '変換失敗'], 500);
		}
	
		return response()->json(['status' => 'success', 'address' => $address]);
	}

	public static function get_android_application_info()
	{
		// tableから最新情報を取得
		$t_application_version = t_application_version_model::get_latest_data();

		$application_flg = false;
		$file_info = [];

		if (!is_null($t_application_version)) {
			
			$version = $t_application_version->version;
			$updated_at = $t_application_version->updated_at;

			// 保存先のディスク
			$disk = Storage::disk('android_application_save_path');

			// version フォルダのパスを指定
			$path = $version . '/';

			// ファイル一覧取得（ファイルが存在するか確認）
			$files = $disk->files($path);

			if(count($files) == 0){

				$application_flg = false;

			}else{

				$application_flg = true;

				// ファイル名取得（バージョンディレクトリ内の1つ目）
				$file_name = $files[0];
				
				// URLを取得
				$file_url = $file_name ? $disk->url($file_name) : null;

				$file_info = (object)[
					"file_url"   => $file_url,
					"file_name"  => $file_name ? basename($file_name) : null,
					"updated_at"  => $updated_at,
					"version"    => $version,
				];
			}		
		}

		// QRコード生成用URL
		$url = route('driver.android_application_download');

		// QRコード生成
		$qr_src = QrCode::size(150)
			->margin(2)
			->color(50, 50, 50)
			->backgroundColor(255, 255, 255, 0)
			->generate($url);

		return (object)[
			'application_flg' => $application_flg,
			'file_info'       => $file_info,
			'qr_src'          => $qr_src,
			'url'             => $url,
		];
	}


}
