<?php

namespace App\Http\Middleware;

use UserHelp;
use Closure;

class AuthUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // if ($request->session()->exists('session_data')) {
        //     // JIKA SUDAH LOGIN
        //     $session_data = $request->session()->get('session_data');
        //     if ($request->is('admin/*') && $session_data['login_as'] == 'ADMIN') {
        //         return $next($request);
        //     }
        // }

        $no_auth_urls = [
            '/',
            'sso/*',
            'login/*',
        ];

        // $allow = false ;
        foreach($no_auth_urls as $url){
            if($request->is($url)){
                return $next($request);
            }
        }

        if($request->is('dashboard')){
            if(UserHelp::is_login()){
                return $next($request);
            }
        }

        if(!$request->is('dashboard')){
            if(UserHelp::is_login()){
                if(UserHelp::is_admin()){
                    if(null === UserHelp::get_selected_role()){
                        if(!$request->is('admin/choose_role')){
                            return redirect('admin/choose_role'); 
                        }
                    }
                }
            }
        }

        if($request->is('share/*')){
            if(UserHelp::is_login()){
                return $next($request);
            }
        }

        if($request->is('mhs/*')){
            if(UserHelp::is_mhs()){
                return $next($request);
            }
        }

        if($request->is('admin/*')){
            if(UserHelp::is_admin()){
                return $next($request);
            }
        }

        if($request->is('super/*')){
            if(UserHelp::get_selected_role() == 'SUPER'){
                return $next($request);
            }
        }     

        return redirect('/'); 

    }
}
