<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\TitleTwoRequset;
use App\Models\Bank\Title_two;
use Illuminate\Http\Request;

class TitleTwoController extends Controller
{
    public function store(TitleTwoRequset $requset){
        //return $requset->tone_text;
        $arrDate = [
            'ttwo_one_id' => $requset->ttwo_one_id,
            'ttwo_text' => $requset->ttwo_text,
            'ttwo_notactive' => 0,

        ];
        Title_two::create($arrDate);
        return redirect()->back()->with("success", "تم الحفظ بنجاح");
        //return "tone_text = " . $requset->tone_text ;
    }
}
