<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Program\Permissionuser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    //

    public function permissionUser(Request $request)
    {
        $user = auth::id();
        //return $request->user()->id;
         $list = Permissionuser::where('type',1)->where('id_user','1')->pluck('id_menu_prog')->toArray();
         //return  $list ;
        //return $user;
        //return $user['id'];
        $users = User::get();
        return view('system.permissionUser', compact('users'))
            ->with(
                [
                    'pageTitle' => "הרשאות למשתמשים",
                    'subTitle' => 'ניהול הרשאות למשתמשים',
                ]
            );
    }


    public function permissionUserShow($idUser)
    {
        return Permissionuser::where('id_user',$idUser)->get();


    }

    public function storeAjax($idUser ,Request $request)
    {
        //return [$request->oper];
        $type="";
        if($request->type=='menu'){
            $type="1";
        }else{
            $type="2";
        }
        if($request->oper=='false'){
            //מחיקה

            $x = Permissionuser::where('id_user', $idUser)
                ->where('type', $type)
                ->where('id_menu_prog', $request->idmenuprog)
                ->delete();

        }else {
            //הוספה
            $arrDate = [
                'id_user' => $idUser,
                'type' => $type,
                'id_menu_prog' =>  $request->idmenuprog,

            ];
            $x = Permissionuser::create($arrDate);
        }

        return $x;
        //$userMenuProg =  Permissionuser::where('id_user',$idUser)->get();
        //return $userMenuProg;

    }
}
