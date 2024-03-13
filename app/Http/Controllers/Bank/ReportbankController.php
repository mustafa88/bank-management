<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank\Banks;
use App\Models\Bank\Banksline;
use App\Models\Bank\Banksdetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportbankController extends Controller
{

    public function mainPageNew(Request $requset){
        //var_dump($requset->fdate);

        //if(!empty($requset->fdate)){
        $reports = array();
        if($requset->has('fdate')){
            $id_bank =$requset->bankid;
            $fromDate =$requset->fdate;
            $toDate=$requset->tdate;
            /**
            $id_bank =1;
            $fromDate ='2021-01-01';
            $toDate='2021-12-31';
             */

            $r1 = $this->report1($id_bank ,$fromDate ,$toDate);
            $reports[] = 'r1';

            $r7 = $this->report7($id_bank ,$fromDate ,$toDate);
            $reports[] = 'r7';

            $r8 = $this->report8($id_bank ,$fromDate ,$toDate);
            $reports[] = 'r8';

            $r2_out = $this->report2($id_bank ,$fromDate ,$toDate ,'1');
            $reports[] = 'r2_out';
            $r3_out = $this->report3($id_bank ,$fromDate ,$toDate ,'1');
            $reports[] = 'r3_out';
            $r4_out = $this->report4($id_bank ,$fromDate ,$toDate ,'1');
            $reports[] = 'r4_out';
            $r5_out = $this->report5($id_bank ,$fromDate ,$toDate ,'1');
            $reports[] = 'r5_out';
            $r6_out = $this->report6($id_bank ,$fromDate ,$toDate ,'1');
            $reports[] = 'r6_out';
            $r9_out = $this->report9($id_bank ,$fromDate ,$toDate ,'1');
            $reports[] = 'r9_out';
            $r10_out = $this->report10($id_bank ,$fromDate ,$toDate ,'1');
            $reports[] = 'r10_out';
            $r11_out = $this->report11($id_bank ,$fromDate ,$toDate ,'1');
            $reports[] = 'r11_out';
            $r12_out = $this->report12($id_bank ,$fromDate ,$toDate ,'1');
            $reports[] = 'r12_out';
            $r13_out = $this->report13($id_bank ,$fromDate ,$toDate ,'1');
            $reports[] = 'r13_out';

            $r2_in = $this->report2($id_bank ,$fromDate ,$toDate ,'2');
            $reports[] = 'r2_in';
            $r3_in = $this->report3($id_bank ,$fromDate ,$toDate ,'2');
            $reports[] = 'r3_in';
            $r4_in = $this->report4($id_bank ,$fromDate ,$toDate ,'2');
            $reports[] = 'r4_in';
            $r5_in = $this->report5($id_bank ,$fromDate ,$toDate ,'2');
            $reports[] = 'r5_in';
            $r6_in = $this->report6($id_bank ,$fromDate ,$toDate ,'2');
            $reports[] = 'r6_in';
            $r9_in = $this->report9($id_bank ,$fromDate ,$toDate ,'2');
            $reports[] = 'r9_in';
            $r10_in = $this->report10($id_bank ,$fromDate ,$toDate ,'2');
            $reports[] = 'r10_in';
            $r11_in = $this->report11($id_bank ,$fromDate ,$toDate ,'2');
            $reports[] = 'r11_in';
            $r12_in = $this->report12($id_bank ,$fromDate ,$toDate ,'2');
            $reports[] = 'r12_in';
            $r13_in = $this->report13($id_bank ,$fromDate ,$toDate ,'2');
            $reports[] = 'r13_in';

            //return $r5_in;
        }else{
            $requset->fdate =  date('Y-01-01');
            $requset->tdate =  date('Y-12-31') ;
        }
        //$requset->fdate = date('Y-01-01');
        //var_dump($requset->fdate);
        //$id_bank =1;
        //$fromDate ='2021-01-01';
        //$toDate='2021-12-31';




        $banks = Banks::with(['enterprise','projects'])->get();

        return view('reports.reportbanknew' , compact('banks',$reports))
            ->with(
                [
                    'pageTitle' => "تقارير بنكيه",
                    'subTitle' => 'بناء تقارير بنكية',
                ]
            );

    }


    function report1($id_bank ,$fromDate ,$toDate){

        /**1
        דוחות:
        סך הכל חובה
        סל הכל זכות
        סך הכל
         */
        $result = Banksline::
        select(
            'id_enter',
            \DB::raw("Count(*) as count_row"),
            \DB::raw("round(sum(amountmandatory),2) as amountmandatory"),
            \DB::raw("round(sum(amountright),2) as amountright"),
            \DB::raw("round(sum(amountright-amountmandatory),2) as total_neto"),
        )
            ->with(['enterprise'])
            ->where('id_bank', $id_bank)
            ->where('datemovement','>=' ,$fromDate)
            ->where('datemovement','<=' ,$toDate)
            ->groupBy('id_enter')
            ->get()
            //->first()
        ;
        return $result;
    }

    function report2($id_bank ,$fromDate ,$toDate ,$type=0){
        /**2
        חודש
        סך הכל חובה
        סל הכל זכות
        סך הכל
         */
        /**
         * $type=0 - חובה - זכות
         * $type=1 - חובה
         * $type=2 - זכות
         */
        $result = Banksline::
        select(
            'id_enter',
            \DB::raw("(DATE_FORMAT(datemovement, '%m-%Y')) as month_year"),
            \DB::raw("Count(*) as count_row"),
            \DB::raw("round(sum(amountmandatory),2) as amountmandatory"),
            \DB::raw("round(sum(amountright),2) as amountright"),
            \DB::raw("round(sum(amountright-amountmandatory),2) as total_neto"),
        )
            ->with(['enterprise'])
            ->where('id_bank', $id_bank)
            ->where('datemovement','>=' ,$fromDate)
            ->where('datemovement','<=' ,$toDate)
            ->groupBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"))
            ->groupBy('id_enter')
            ->orderBy('id_enter')
            ->orderBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"));

        if($type==1){
            // רק חובה
            $result->where('amountmandatory',"!=" ,0);
        }elseif ($type==2){
            //רק זכות
            $result->where('amountright',"!=" ,0);
        }

        $result = $result->get();

        ;
        return $result;
    }

    function report3($id_bank ,$fromDate ,$toDate ,$type=0){
        /**3
        סוג תנועה
        סך הכל חובה
        סל הכל זכות
        סך הכל
         */
        $result = Banksline::
        select(
            'id_enter',
            'id_titletwo',
            \DB::raw("Count(*) as count_row"),
            \DB::raw("round(sum(amountmandatory),2) as amountmandatory"),
            \DB::raw("round(sum(amountright),2) as amountright"),
            \DB::raw("round(sum(amountright-amountmandatory),2) as total_neto"),
        )
            ->with(['enterprise' ,'titletwo'])
            ->where('id_bank', $id_bank)
            ->where('datemovement','>=' ,$fromDate)
            ->where('datemovement','<=' ,$toDate)
            ->groupBy('id_enter')
            ->groupBy('id_titletwo');

        if($type==1){
            // רק חובה
            $result->where('amountmandatory',"!=" ,0);
        }elseif ($type==2){
            //רק זכות
            $result->where('amountright',"!=" ,0);
        }

        $result = $result->get();

        ;
        return $result;
    }

    function report4($id_bank ,$fromDate ,$toDate ,$type=0){
        /**4
        חודש
        סוג תנועה
        סך הכל חובה
        סל הכל זכות
        סך הכל
         */
        $result = Banksline::
        select(
            'id_enter',
            'id_titletwo',
            \DB::raw("(DATE_FORMAT(datemovement, '%m-%Y')) as month_year"),
            \DB::raw("Count(*) as count_row"),
            \DB::raw("round(sum(amountmandatory),2) as amountmandatory"),
            \DB::raw("round(sum(amountright),2) as amountright"),
            \DB::raw("round(sum(amountright-amountmandatory),2) as total_neto"),
        )
            ->with(['enterprise' ,'titletwo'])
            ->where('id_bank', $id_bank)
            ->where('datemovement','>=' ,$fromDate)
            ->where('datemovement','<=' ,$toDate)
            ->groupBy('id_enter')
            ->groupBy('id_titletwo')
            ->groupBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"))
            ->orderBy('id_enter')
            ->orderBy('id_titletwo')
            ->orderBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"));

        if($type==1){
            // רק חובה
            $result->where('amountmandatory',"!=" ,0);
        }elseif ($type==2){
            //רק זכות
            $result->where('amountright',"!=" ,0);
        }

        $result = $result->get();

        return $result;
    }

    function report5($id_bank ,$fromDate ,$toDate ,$type=0){
        /*
         * עמותה
         * סוג תנועה
         * פרויקט
         * חובה
         * זכות
         * נטו
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp','title_two.ttwo_text','projects.name as proj'
                ,DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                ,DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                ,DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')

            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')

            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')


            ->where('banksline.id_bank','=' ,$id_bank)
            ->where('banksline.datemovement','>=' ,$fromDate)
            ->where('banksline.datemovement','<=' ,$toDate)
            ->groupBy('enterprise.name','title_two.ttwo_text','projects.name')

            ->orderBy('enterprise.name')
            ->orderBy('title_two.ttwo_text')
            ->orderBy('projects.name');

        if($type==1){
            // רק חובה
            $result->where('banksline.amountmandatory',"!=" ,0);
        }elseif ($type==2){
            //רק זכות
            $result->where('banksline.amountright',"!=" ,0);
        }

        $result = $result->get();

        return json_decode(json_encode($result->toArray()), true);
    }

    function report6($id_bank ,$fromDate ,$toDate ,$type=0){
        /*
         * עמותה
         * סוג תנועה
         * פרויקט
         * עיר
         * חובה
         * זכות
         * נטו
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp','title_two.ttwo_text','projects.name as proj'
            ,'city.city_name'
                ,DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                ,DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                ,DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')

            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')

            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')

            ->where('banksline.id_bank','=' ,$id_bank)
            ->where('banksline.datemovement','>=' ,$fromDate)
            ->where('banksline.datemovement','<=' ,$toDate)
            ->groupBy('enterprise.name','title_two.ttwo_text','projects.name','city.city_name')

            ->orderBy('enterprise.name')
            ->orderBy('title_two.ttwo_text')
            ->orderBy('projects.name')
            ->orderBy('city.city_name');

        if($type==1){
            // רק חובה
            $result->where('banksline.amountmandatory',"!=" ,0);
        }elseif ($type==2){
            //רק זכות
            $result->where('banksline.amountright',"!=" ,0);
        }

        $result = $result->get();
        return json_decode(json_encode($result->toArray()), true);
        //return $result;
    }


    function report7($id_bank ,$fromDate ,$toDate ,$type=0){
        /**7
         * עמותה
         * פרויקט
         * סך הכל חובה
         * סל הכל זכות
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp','projects.name as proj'
                ,DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                ,DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                ,DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')

            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')

            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')


            ->where('banksline.id_bank','=' ,$id_bank)
            ->where('banksline.datemovement','>=' ,$fromDate)
            ->where('banksline.datemovement','<=' ,$toDate)
            ->groupBy('enterprise.name','projects.name')

            ->orderBy('enterprise.name')
            ->orderBy('projects.name');

        if($type==1){
            // רק חובה
            $result->where('banksline.amountmandatory',"!=" ,0);
        }elseif ($type==2){
            //רק זכות
            $result->where('banksline.amountright',"!=" ,0);
        }

        $result = $result->get();

        return $result;
    }

    function report8($id_bank ,$fromDate ,$toDate ,$type=0){
        /**8
         * עמותה
         * פרויקט
         * עיר
         * סך הכל חובה
         * סל הכל זכות
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp','projects.name as proj'
                ,'city.city_name'
                ,DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                ,DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                ,DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')

            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')

            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')

            ->where('banksline.id_bank','=' ,$id_bank)
            ->where('banksline.datemovement','>=' ,$fromDate)
            ->where('banksline.datemovement','<=' ,$toDate)
            ->groupBy('enterprise.name','projects.name','city.city_name')

            ->orderBy('enterprise.name')
            ->orderBy('projects.name')
            ->orderBy('city.city_name');

        if($type==1){
            // רק חובה
            $result->where('banksline.amountmandatory',"!=" ,0);
        }elseif ($type==2){
            //רק זכות
            $result->where('banksline.amountright',"!=" ,0);
        }

        $result = $result->get();

        return $result;
    }



    function report9($id_bank ,$fromDate ,$toDate ,$type=0){
        /*
         * עמותה
         * סוג תנועה
         * פרויקט
         * עיר
         * סוג חובה/סוג זכות
         * חובה
         * זכות
         * נטו
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp','title_two.ttwo_text','projects.name as proj'
                ,'city.city_name'
                ,'expense.name as expensename'
                ,'income.name as incomename'
                ,DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                ,DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                ,DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')

            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')

            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')

            ->leftJoin('expense', 'expense.id', '=', 'banksdetail.id_expens')
            ->leftJoin('income', 'income.id', '=', 'banksdetail.id_incom')

            ->where('banksline.id_bank','=' ,$id_bank)
            ->where('banksline.datemovement','>=' ,$fromDate)
            ->where('banksline.datemovement','<=' ,$toDate)
            ->groupBy('enterprise.name','title_two.ttwo_text','projects.name'
                ,'city.city_name','expense.name','income.name')

            ->orderBy('enterprise.name')
            ->orderBy('title_two.ttwo_text')
            ->orderBy('projects.name')
            ->orderBy('city.city_name')
            ->orderBy('expense.name')
            ->orderBy('income.name')
        ;

        if($type==1){
            // רק חובה
            $result->where('banksline.amountmandatory',"!=" ,0);
            $result->whereNotNull('expense.name');
        }elseif ($type==2){
            //רק זכות
            $result->where('banksline.amountright',"!=" ,0);
            $result->whereNotNull('income.name');
        }

        $result = $result->get();

        return json_decode(json_encode($result->toArray()), true);
    }

    function report10($id_bank ,$fromDate ,$toDate ,$type=0){
        /*
         * עמותה
         * סוג תנועה
         * פרויקט
         * עיר
         * סוג חובה/סוג זכות
         * חודש
         * חובה/זכות
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp','title_two.ttwo_text','projects.name as proj'
                ,'city.city_name'
                ,'expense.name as expensename'
                ,'income.name as incomename'
                ,\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y')) as month_year")
                ,DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                ,DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                ,DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')

            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')

            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')

            ->leftJoin('expense', 'expense.id', '=', 'banksdetail.id_expens')
            ->leftJoin('income', 'income.id', '=', 'banksdetail.id_incom')

            ->where('banksline.id_bank','=' ,$id_bank)
            ->where('banksline.datemovement','>=' ,$fromDate)
            ->where('banksline.datemovement','<=' ,$toDate)
            ->groupBy('enterprise.name','title_two.ttwo_text','projects.name'
                ,'city.city_name','expense.name','income.name')
            ->groupBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"))

            ->orderBy('enterprise.name')
            ->orderBy('title_two.ttwo_text')
            ->orderBy('projects.name')
            ->orderBy('city.city_name')
            ->orderBy('expense.name')
            ->orderBy('income.name')
            ->orderBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"))
        ;

        if($type==1){
            // רק חובה
            $result->where('banksline.amountmandatory',"!=" ,0);
            $result->whereNotNull('expense.name');
        }elseif ($type==2){
            //רק זכות
            $result->where('banksline.amountright',"!=" ,0);
            $result->whereNotNull('income.name');
        }

        $result = $result->get();

        return json_decode(json_encode($result->toArray()), true);
    }


    function report11($id_bank ,$fromDate ,$toDate ,$type=0){
        /*
         * עמותה
         * פרויקט
         * קמפיין
         * חובה/זכות
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp','projects.name as proj'
               ,'campaigns.name_camp'
                ,DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                ,DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                ,DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')

            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')

            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')

            ->leftJoin('expense', 'expense.id', '=', 'banksdetail.id_expens')
            ->leftJoin('income', 'income.id', '=', 'banksdetail.id_incom')

            ->leftJoin('campaigns', 'campaigns.id', '=', 'banksdetail.id_campn')

            ->where('banksline.id_bank','=' ,$id_bank)
            ->where('banksline.datemovement','>=' ,$fromDate)
            ->where('banksline.datemovement','<=' ,$toDate)
            ->whereNotNull('campaigns.name_camp')
            ->groupBy('enterprise.name','projects.name'

            )
            ->groupBy('campaigns.name_camp')


            ->orderBy('enterprise.name')
            ->orderBy('projects.name')
        ;

        if($type==1){
            // רק חובה
            $result->where('banksline.amountmandatory',"!=" ,0);
            $result->whereNotNull('expense.name');
        }elseif ($type==2){
            //רק זכות
            $result->where('banksline.amountright',"!=" ,0);
            $result->whereNotNull('income.name');
        }

        $result = $result->get();

        return json_decode(json_encode($result->toArray()), true);
    }

    function report12($id_bank ,$fromDate ,$toDate ,$type=0){
        /*
         * עמותה
         * פרויקט
         * חודש
         * קמפיין
         * חובה/זכות
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp','projects.name as proj'
                //,'city.city_name'
                ,\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y')) as month_year")
                ,'campaigns.name_camp'
                ,DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                ,DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                ,DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')

            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')

            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')

            ->leftJoin('expense', 'expense.id', '=', 'banksdetail.id_expens')
            ->leftJoin('income', 'income.id', '=', 'banksdetail.id_incom')

            ->leftJoin('campaigns', 'campaigns.id', '=', 'banksdetail.id_campn')

            ->where('banksline.id_bank','=' ,$id_bank)
            ->where('banksline.datemovement','>=' ,$fromDate)
            ->where('banksline.datemovement','<=' ,$toDate)
            ->whereNotNull('campaigns.name_camp')
            ->groupBy('enterprise.name','projects.name'
            )
            ->groupBy('campaigns.name_camp')
            ->groupBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"))


            ->orderBy('enterprise.name')
            ->orderBy('projects.name')
            ->orderBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"))
        ;

        if($type==1){
            // רק חובה
            $result->where('banksline.amountmandatory',"!=" ,0);
            $result->whereNotNull('expense.name');
        }elseif ($type==2){
            //רק זכות
            $result->where('banksline.amountright',"!=" ,0);
            $result->whereNotNull('income.name');
        }

        $result = $result->get();

        return json_decode(json_encode($result->toArray()), true);
    }

    function report13($id_bank ,$fromDate ,$toDate ,$type=0){
        /*
         * עמותה
         * פרויקט
         * עיר
         * קמפיין
         * חובה/זכות
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp','projects.name as proj'
                ,'city.city_name'
                ,'campaigns.name_camp'
                ,DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                ,DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                ,DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')

            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')

            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')

            ->leftJoin('expense', 'expense.id', '=', 'banksdetail.id_expens')
            ->leftJoin('income', 'income.id', '=', 'banksdetail.id_incom')

            ->leftJoin('campaigns', 'campaigns.id', '=', 'banksdetail.id_campn')

            ->where('banksline.id_bank','=' ,$id_bank)
            ->where('banksline.datemovement','>=' ,$fromDate)
            ->where('banksline.datemovement','<=' ,$toDate)
            ->whereNotNull('campaigns.name_camp')
            ->groupBy('enterprise.name','projects.name'
            ,'city.city_name'
            )
            ->groupBy('campaigns.name_camp')


            ->orderBy('enterprise.name')
            ->orderBy('projects.name')
            ->orderBy('city.city_name')
        ;

        if($type==1){
            // רק חובה
            $result->where('banksline.amountmandatory',"!=" ,0);
            $result->whereNotNull('expense.name');
        }elseif ($type==2){
            //רק זכות
            $result->where('banksline.amountright',"!=" ,0);
            $result->whereNotNull('income.name');
        }

        $result = $result->get();

        return json_decode(json_encode($result->toArray()), true);
    }
}
