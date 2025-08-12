<?php

namespace App\Original\User;

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

class UserCommon
{



	public static function test()
	{
		

		return "";
	}

}
