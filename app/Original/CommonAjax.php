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
use App\Models\MAddressModel;

// Model ↑

// Request ↓

// Request ↑

class CommonAjax
{


	public static function SearchPostalCodeForAddress(Request $request)
	{
		$postal_code = "";
	
		try {
			
			$address = $request->address;			
	
			$postal_code = MAddressModel::GetPostalCodeForAddress($address);
	
		} catch (Exception $e) {

			return response()->json(['status' => 'error', 'message' => '変換失敗'], 500);
		}
	
		return response()->json(['status' => 'success', 'postal_code' => $postal_code]);
	}

	public static function SearchAddressForPostalCode(Request $request)
	{
		$address = "";
	
		try {

			$postal_code = $request->postal_code;			
	
			$address = MAddressModel::GetAddressForPostalCode($postal_code);
	
		} catch (Exception $e) {
			
			return response()->json(['status' => 'error', 'message' => '変換失敗'], 500);
		}
	
		return response()->json(['status' => 'success', 'address' => $address]);
	}
}
