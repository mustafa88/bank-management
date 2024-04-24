<?php

namespace App\Http\Middleware;

use App\Models\Program\Pprogram;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;
use App\Models\Program\Permissionuser;
//use Illuminate\Support\Facades\Request;



class MidPermissionProgram
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$programName): Response
    {
        //return $next($request);
        if($request->ajax()){
            return $next($request);
        }

        $idUser = Auth::user()->id;

        $userName = Auth::user()->username;

        if($userName=='admin'){
            return $next($request);
        }

        //שם תונכה/ תוכניות למשתמש שיש לו הרשאה להם עם אותו שם ROUTE
        //מצב שיש יותר מתוכנה אחד - אם היא מקבלת משתנים שונים בכל כניסה - כמו בנקים
        $programUserRoute= \DB::table('Permissionuser')
        ->leftJoin('pprogram', 'Permissionuser.id_menu_prog', '=', 'pprogram.id_program')
            ->where('Permissionuser.type','2')
            ->where('Permissionuser.id_user',$idUser)
            ->where('pprogram.routename',$programName)
            ->get();

        //join with table pprogram as some route name
        $programUserRoute = json_decode(json_encode($programUserRoute), true);

        //ddd($programUserRoute);
        if(!($programUserRoute)){
            //תוכנה לא קיימת למשתמש
            return redirect('/');
        }else{
            return $next($request);
        }


        /**
         * צריך לחזור ולחשוב עך פתרון - אם יש משתנים בזמן קראה לתונה

        $paramRoute = $request->route()->parameters;
        //dd($paramRoute);
        //dd($paramRoute,$programUserRoute);
        //ddd($paramRoute);
        $flg=true;
        if(count($paramRoute)>0){
            foreach ($programUserRoute as $item) {
                if($item['param']==null){
                    $flg=false;
                    continue;
                }
                $paramProgram = json_decode($item['param'], true);
                //צריך שבמערך $paramProgram  - יהיו כל השמנתים שיש במערך $paramRoute
                //dd($paramProgram);
                $flg=true;
                foreach ($paramProgram as $index_f => $item_f) {
                    if(!isset($paramRoute[$index_f]) or $paramRoute[$index_f]!=$item_f){
                        $flg=false;
                        break;
                    }
                }
                if($flg){
                    break;
                }
            }
        }

        if($flg){
            //יש למשתמש הרשאה
            //מערך $paramRoute - מכיל כל בהפרמטרים שיש במערך  $paramProgram
            return $next($request);
        }else{
            //אין למשתמש הרשאה
            return redirect('/');
        }
        **/

    }
}
