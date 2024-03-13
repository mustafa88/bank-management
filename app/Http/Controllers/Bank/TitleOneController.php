<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\TitleOneRequset;
use Illuminate\Http\Request;
use App\Models\Bank\Title_one;
class TitleOneController extends Controller
{
    //

    public function test(){
        //return Title_one::where('tone_id', '=', 1)->first();
        //return Title_one::where('tone_id', '=', 1)->first();
        //return Title_one::find(1)->titleTwo;
        //return Title_one::find(1)->titleTwo;

        /**
        return Title_one::with(['titleTwo' => function ($q){
        $q->select('ttwo_id','ttwo_one_id','ttwo_text');
        }])->get();
         */


        //$titleone =  Title_one::with(['titleTwo'])->get();

        //$titleone =  Title_one::whereHas('titleTwo')->with(['titleTwo'])->get();
        //$titleone->makeVisible(['tone_text']);
        //$titleone->makeHidden(['tone_text']);
        //return $titleone[0]->titleTwo;

        $titleone =  Title_one::with(['titleTwo'])->get();
        //$titleone =  Title_one::find(1)->titleTwo;
        return $titleone;
    }

    public function showTable(){
        $titleone = Title_one::with(['titleTwo'])->get()->toArray();
        //return $titleone;
        //var_dump(($titleone));
        //var_dump(compact('titleone'));
        //ddd($titleone,compact('titleone'));
        //ddd($titleone);
        //$titleone[0]['tone_text']='ee';
        //$titleone[0]['title_two'][0]=1;
        //foreach ($titleone  as $v){
         //   var_dump($v);
        //}
           return view('managetable.titletable', compact('titleone'))
        //return view('managetable.titletable',['titleone' => $titleone])
            ->with(
                [
                    'pageTitle' => "כותרת ראשית",
                    'subTitle' => 'ניהול שדות כותרת ראשית',
                    //"success" => "تم الحفظ بنجاح"
                ]
            );
    }

    public function store(TitleOneRequset $requset){
        //return $requset->tone_text;
        $arrDate = [
            'tone_text' => $requset->tone_text,
            'tone_notactive' => 0,
        ];
        Title_one::create($arrDate);
        return redirect()->back()->with("success", "تم الحفظ بنجاح");
        //return "tone_text = " . $requset->tone_text ;
    }
}
