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
// Model ↑

// Request ↓
// Request ↑

class CommonArray
{

	public static function GetTextAlignClasses()
	{

		$textAlignClasses = ["text-start", "text-center", "text-end"];

		return $textAlignClasses;
	}

}
