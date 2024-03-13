<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\bank\CityRequset;
use App\Models\Bank\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function showTable(){
        $city = City::get()->toArray();
        return view('managetable.city', compact('city'))
            ->with(
                [
                    'pageTitle' => "جدول البلدان",
                    'subTitle' => 'قائمة بجميع البلدان',

                ]
            );
    }

    public function store(CityRequset $requset){
        $arrDate = [
            'city_name' => $requset->city_name,
        ];
        City::create($arrDate);
        return redirect()->back()->with("success", "تم الحفظ بنجاح");
    }
}
