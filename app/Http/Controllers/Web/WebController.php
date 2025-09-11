<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


// controller作成時ここからコピー↓
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Original\Common;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\Snappy\Facades\SnappyPdf as SnappyPDF;
use League\Csv\Reader;
use Illuminate\Support\Facades\File;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Picqer\Barcode\BarcodeGeneratorPNG;
// controller作成時ここまでコピー↑

class WebController extends Controller
{
    function index(Request $request)
	{	
		$qr_src = Common::test();

		return view('Web.Screen.index', compact('qr_src'));
	}


	
}
