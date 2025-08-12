<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function index(Request $request)
	{

        if(1 == 1){
            return redirect(route('user.login'));
        }else{
            return redirect(route('user.top_menu'));
        }
	
	}
    
	function login(Request $request)
	{	
        $demo = "";
		return view('User.Screen.login', compact('demo'));
	}

    function login_check(Request $request)
	{

		$errors = [];
        $not_entered = [];
        // ★ 共通返却ロジックをクロージャとして定義
		$return_result = function($errors) {
		
            $errors = (object)$errors;
            session()->flash('errors',$errors);
            return redirect(route('user.login'))->withInput();            
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


		

		if (1 == 2) {
			session()->put(['user_id' => 1]);
			session()->save();

			return redirect(route('user.index'));
		} else {

			$errors["login_error_message"]= "test";
            return $return_result($errors);   
		}
	}

    function top_menu(Request $request)
	{	
        $demo = "";
		return view('User.Screen.top_menu', compact('demo'));
	}
}
