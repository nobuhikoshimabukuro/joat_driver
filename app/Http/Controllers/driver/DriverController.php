<?php

namespace App\Http\Controllers\driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverController extends Controller
{

    function index(Request $request)
	{

        if(1 == 1){
            return redirect(route('driver.login'));
        }else{
            return redirect(route('driver.top_menu'));
        }
	
	}
    
	function login(Request $request)
	{	
        $demo = "";
		return view('driver.screen.login', compact('demo'));
	}

    function login_check(Request $request)
	{

		$errors = [];
        $not_entered = [];
        // ★ 共通返却ロジックをクロージャとして定義
		$return_result = function($errors) {
		
            $errors = (object)$errors;
            session()->flash('errors',$errors);
            return redirect(route('driver.login'))->withInput();            
		};

		$driver_cd = $request->driver_cd;
        $password = $request->password;

		if(trim($driver_cd) == "" || is_null($driver_cd)){    
            $errors["driver_cd"]= 1;
            $not_entered[] = "ドライバーコード";            
        }

        if(trim($password) == "" || is_null($password)){      
            $errors["password"]= 1;
            $not_entered[] = "パスワード";            
        }

		if (!empty($not_entered)) {            
            $errors["login_error_message"]= implode('、', $not_entered) . 'は必須項目です。';
            return $return_result($errors);
        }       


		

		if (1 == 2) {
			session()->put(['driver_id' => 1]);
			session()->save();

			return redirect(route('driver.index'));
		} else {

			$errors["login_error_message"]= "test";
            return $return_result($errors);   
		}
	}

}
