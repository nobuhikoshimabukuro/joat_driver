<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Original\Manager\ManagerCommon;

class ManagerAuth
{
    public function handle(Request $request, Closure $next)
    {
        $session_info = ManagerCommon::GetManagerUserInfo();

        if (!$session_info->login_status) {

            session()->put([
                'manager_after_login_url' => $request->fullUrl()
            ]);
            session()->save();            
            // 未ログイン時の遷移先
            return redirect()->route('manager.index');
        }

        return $next($request);
    }
}
