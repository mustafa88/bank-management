<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank\Banksline;
use App\Models\Bank\City;
use App\Models\Bank\Enterprise;
use App\Models\Bank\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ReportProjectController extends Controller
{

    public function mainPageFinal(Request $requset)
    {
        //$enterprise = Enterprise::all();
        $enterprise = Enterprise::with(['project'])->get();
        if(!isset($requset->fdate))
        {
            $requset->fdate = date('Y-01-01');
            $requset->tdate = date('Y-12-31');
        }


        $tables = $array_t1 = array();;
        if ($requset->has('showData')) {


            //$enterp_proj = $requset->enterp;
            $fromDate = $requset->fdate;
            $toDate = $requset->tdate;


            $enterp_proj = explode("*", $requset->enterp);
            $enterp = $enterp_proj[0];
            $proj = $enterp_proj[1];

            if($fromDate==null and $toDate==null)
            {
                exit;
            }

            //כל סוגי החובה שהיו בתקופה
            $titletwo_hova = Banksline::select('id_titletwo', 'title_two.ttwo_text')
                ->distinct()
                ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
                ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
                ->where('banksline.id_enter', $enterp)
                ->where('banksdetail.id_proj', $proj)
                ->where('banksline.datemovement', '>=', $fromDate)
                ->where('banksline.datemovement', '<=', $toDate)
                ->where('banksline.amountmandatory', '!=', 0)
                ->get();
            //כל סוגי זכות שהיות בתקופה
            $titletwo_zchut = Banksline::select('id_titletwo', 'title_two.ttwo_text')
                ->distinct()
                ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
                ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
                ->where('banksline.id_enter', $enterp)
                ->where('banksdetail.id_proj', $proj)
                ->where('banksline.datemovement', '>=', $fromDate)
                ->where('banksline.datemovement', '<=', $toDate)
                ->where('banksline.amountright', '!=', 0)
                ->get();


            $all_sapak = Banksline::select('banksdetail.id_expens', 'expense.name')
                ->distinct()
                ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
                ->leftJoin('expense', 'expense.id', '=', 'banksdetail.id_expens')
                ->whereNotNull('banksdetail.id_expens')
                ->where('banksline.id_enter', $enterp)
                ->where('banksdetail.id_proj', $proj)
                ->where('banksline.datemovement', '>=', $fromDate)
                ->where('banksline.datemovement', '<=', $toDate)
                ->get();
            // return $all_sapak;


            $project = Projects::find($proj);
            $project_name = $project['name'];

            $city = City::WhereHas('projects', function ($query) use ($proj) {
                $query->where('projects.id', '=', $proj);
            })->get();


            $array = array();
            $array[0][0] = '';
            foreach ($city as $key => $item) {
                $array[0][$key + 1] = $item['city_name'];
            }
            $array[0][] = 'المجموع';

            $array_t1 = $array_t2 = $array_t3 = $array_t4 =  $array;
            $array_t5 = array();
            $t1 = function ($enterp, $proj, $fromDate, $toDate, $city = null, $titletwo = null) {

                $result = DB::table('banksdetail')
                    ->select(DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                        , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                    )
                    ->leftJoin('banksline', 'banksdetail.id_line', '=', 'banksline.id_line')
                    ->where('banksline.id_enter', $enterp)
                    ->where('banksdetail.id_proj', $proj)
                    ->where('banksline.datemovement', '>=', $fromDate)
                    ->where('banksline.datemovement', '<=', $toDate);
                if ($city != null) {
                    $result->where('banksdetail.id_city', $city);
                }
                if ($titletwo != null) {
                    $result->where('banksline.id_titletwo', $titletwo);
                }
                $result = $result->get();
                //$x = (array)$result ;
                $result = json_decode(json_encode($result), true);

                $result[0]['amountmandatory'] = number_format($result[0]['amountmandatory'], 2);
                $result[0]['amountright'] = number_format($result[0]['amountright'], 2);
                return $result;

            };

            $t2_yetra = function ($enterp, $proj, $fromDate ,$toDate, $city = null) {
                $result = DB::table('banksdetail')
                    ->select(
                        DB::raw(
                            "(round(sum(banksdetail.amountright),2) - round(sum(banksdetail.amountmandatory),2)) as yetra"
                        )
                    )
                    ->leftJoin('banksline', 'banksdetail.id_line', '=', 'banksline.id_line')
                    ->where('banksline.id_enter', $enterp)
                    ->where('banksdetail.id_proj', $proj);

                if ($fromDate != null) {
                    $result->where('banksline.datemovement', '<', $fromDate);
                }

                if ($toDate != null) {
                    $result->where('banksline.datemovement', '<=', $toDate);
                }

                if ($city != null) {
                    $result->where('banksdetail.id_city', $city);
                }

                $result = $result->get();
                //$x = (array)$result ;
                $result = json_decode(json_encode($result), true);


                $result[0]['yetra'] = number_format($result[0]['yetra'], 2);
                return $result;

            };

            $t3_spak = function ($enterp, $proj, $fromDate, $toDate, $city = null, $numspak = null) {
                $result = DB::table('banksline')
                    ->select(
                        DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                    )

                    ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
                    ->leftJoin('expense', 'expense.id', '=', 'banksdetail.id_expens')
                    ->where('banksline.id_enter', $enterp)
                    ->where('banksdetail.id_proj', $proj)
                    ->where('banksline.id_titletwo', 2)
                    ->where('banksline.datemovement', '>=', $fromDate)
                    ->where('banksline.datemovement', '<=', $toDate);

                if ($city != null) {
                    $result->where('banksdetail.id_city', $city);
                }
                if ($numspak != null) {
                    $result->where('banksdetail.id_expens', $numspak);
                }
                $result = $result->get();
                $result =  json_decode(json_encode($result), true);
                $result[0]['amountmandatory'] = number_format($result[0]['amountmandatory'], 2);
                return $result;
            };

            //הוראות קבע
            $t5_horotkvg = function ($enterp, $proj, $month, $city  ,$sogzchot=null,$soghyov=null){
                $result = DB::table('banksline')
                    /**
                    ->select(
                        DB::raw("round(sum(banksdetail.amountright),2) as amountright"),
                        DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory"),
                        DB::raw("round(sum(banksdetail.amountright - banksdetail.amountmandatory),2) as total")
                    )
                    */
                    ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')

                    ->where('banksline.id_enter', $enterp)
                    ->where('banksdetail.id_proj', $proj)
                    //5=תקבולים הוראות קבע
                    //6=חזרות הוראות קבע
                    //->whereIn('banksline.id_titletwo', [5,6])
                    ->whereDate('banksline.datemovement', 'LIKE', "{$month}%");

                    if($sogzchot!=null){
                        $result->addSelect( DB::raw("round(sum(banksdetail.amountright),2) as sumall"));
                        $result->where('banksline.id_titletwo',$sogzchot);
                    }else{
                        $result->addSelect( DB::raw("round(sum(banksdetail.amountmandatory),2) as sumall"));
                        $result->where('banksline.id_titletwo',$soghyov);
                    }


                 if ($city != null) {
                     $result->where('banksdetail.id_city', $city);
                 }

                $result =  $result->get();

                $result =  json_decode(json_encode($result), true);
                //return  number_format($result[0]['sumall'], 2);
                return  $result[0]['sumall'];
                /**
                $result[0]['amountmandatory'] = number_format($result[0]['amountmandatory'], 2);
                $result[0]['amountright'] = number_format($result[0]['amountright'], 2);
                $result[0]['total'] = number_format($result[0]['total'], 2);
                return array(
                    'amountmandatory'=>$result[0]['amountmandatory'],
                    'amountright'=>$result[0]['amountright'],
                    'total'=> $result[0]['total']
                );
                 * */
            };



            $array_t1[1][0] = 'יתרת פתיחה';
            $array_t1[2][0] = 'הוצאה';
            $array_t1[3][0] = 'הכנסה';
            $array_t1[4][0] = 'יתרת סגירה';
            foreach ($city as $key => $item) {
                $result = $t1($enterp, $proj, $fromDate, $toDate , $item['city_id']);
                $result_yetra_open = $t2_yetra ($enterp, $proj, $fromDate ,null, $item['city_id']);
                $result_yetra_end = $t2_yetra ($enterp, $proj, null ,$toDate, $item['city_id']);

                $array_t1[1][$key + 1] = $result_yetra_open[0]['yetra'];
                $array_t1[2][$key + 1] = $result[0]['amountmandatory'];
                $array_t1[3][$key + 1] = $result[0]['amountright'];
                $array_t1[4][$key + 1] =  $result_yetra_end[0]['yetra'];
            }

            $result = $t1($enterp, $proj, $fromDate, $toDate);

            //$result_yetra = $t2_yetra ($enterp, $proj, $fromDate, $city = null);
            $result_yetra_sum_open = $t2_yetra ($enterp, $proj, $fromDate ,null, null);
            $result_yetra_sum_end= $t2_yetra ($enterp, $proj, null ,$toDate, null);
            $array_t1[1][] = $result_yetra_sum_open[0]['yetra'];
            $array_t1[2][] = $result[0]['amountmandatory'];
            $array_t1[3][] = $result[0]['amountright'];
            $array_t1[4][] = $result_yetra_sum_end[0]['yetra'];



            $array_t2[0][0] = $project_name . ' - פירוט הוצאות';

            foreach ($titletwo_hova as $key_hova => $item_hova) {
                $array_t2[$key_hova + 1][0] = $item_hova['ttwo_text'];
                foreach ($city as $key_city => $item_city) {
                    $result = $t1($enterp, $proj, $fromDate, $toDate, $item_city['city_id'], $item_hova['id_titletwo']);
                    $array_t2[$key_hova + 1][$key_city + 1] = $result[0]['amountmandatory'];
                }
                $result = $t1($enterp, $proj, $fromDate, $toDate, null, $item_hova['id_titletwo']);
                $array_t2[$key_hova+1][]= $result[0]['amountmandatory'];

            }

            $array_t3[0][0] = $project_name . ' - פירוט הכנסה';
            foreach ($titletwo_zchut as $key_zchut => $item_zchut) {
                $array_t3[$key_zchut + 1][0] = $item_zchut['ttwo_text'];
                foreach ($city as $key_city => $item_city) {
                    $result = $t1($enterp, $proj, $fromDate, $toDate, $item_city['city_id'], $item_zchut['id_titletwo']);
                    $array_t3[$key_zchut + 1][$key_city + 1] = $result[0]['amountright'];
                }
                $result = $t1($enterp, $proj, $fromDate, $toDate, null, $item_zchut['id_titletwo']);
                $array_t3[$key_zchut+1][]= $result[0]['amountright'];

            }



            //$xxx = $t3_spak($enterp, $proj, $fromDate, $toDate, null, $all_sapak[0]['id_expens']);
            //return $xxx;
            //$xxx = $t3_spak($$enterp, $proj, $fromDate, $toDate, $city = null, $all_sapak[0]['id_expens']);

            /**
            foreach ($all_sapak as $key_sapak => $item_sapak) {
                $array_t4[$key_zchut + 1][0] = $item_zchut['ttwo_text'];
                foreach ($city as $key_city => $item_city) {
                    $result = $t1($enterp, $proj, $fromDate, $toDate, $item_city['city_id'], $item_zchut['id_titletwo']);
                    $array_t4[$key_zchut + 1][$key_city + 1] = $result[0]['amountright'];
                }
                $result = $t1($enterp, $proj, $fromDate, $toDate, null, $item_zchut['id_titletwo']);
                $array_t4[$key_zchut+1][]= $result[0]['amountright'];

            }
             **/


            $array_t4[0][0] = $project_name . ' - הוצאה לספקים';
            foreach ($all_sapak as $key_sapak => $item_sapak) {
                $array_t4[$key_sapak + 1][0] = $item_sapak['name'];
                foreach ($city as $key_city => $item_city) {
                    $result = $t3_spak($enterp, $proj, $fromDate, $toDate, $item_city['city_id'], $item_sapak['id_expens']);
                    $array_t4[$key_sapak + 1][$key_city + 1] = $result[0]['amountmandatory'];
                }
                $result = $t3_spak($enterp, $proj, $fromDate, $toDate, null, $item_sapak['id_expens']);
                $array_t4[$key_sapak+1][]= $result[0]['amountmandatory'];
            }

            //return $array_t4;

            foreach ($city as $key_city => $item_city) {
                $city_id = $item_city['city_id'];
                $tmp = array();
                $tmp[0][0] = $item_city['city_name'] . ' - הוראות קבע';
                $tmp[0][1] = 'תקבולים';
                $tmp[0][2] = 'חזרות';
                $tmp[0][3] = 'עמלת  בנק ה.ק';
                $tmp[0][4] = 'עמלת  שקד';
                $tmp[0][5] = 'סך הכל';

                $start_date =  Carbon::parse($fromDate);
                $end_date =  Carbon::parse($toDate);
                for($i=1;$start_date <= $end_date;$i++){
                    $dateMonth = $start_date->format('Y-m');

                    $result_amountright = $t5_horotkvg ($enterp, $proj, $dateMonth,  $city_id ,5);
                    $result_amountmandatory_hazor =  $t5_horotkvg ($enterp, $proj, $dateMonth,  $city_id ,null,6);
                    $result_amountmandatory_amltbnk =  $t5_horotkvg ($enterp, $proj, $dateMonth,  $city_id ,null,9);
                    $result_amountmandatory_shkd = $t3_spak($enterp, $proj, $dateMonth.'-01', $dateMonth.'-31', $city_id, 5);
                    $result_amountmandatory_shkd = str_replace(',', '', $result_amountmandatory_shkd[0]['amountmandatory']);

                    $tmp[$i][0] =$dateMonth;
                    $tmp[$i][1] =number_format($result_amountright, 2);
                    $tmp[$i][2] =number_format($result_amountmandatory_hazor*-1, 2);
                    $tmp[$i][3] =number_format($result_amountmandatory_amltbnk*-1, 2);
                    $tmp[$i][4] =number_format($result_amountmandatory_shkd*-1, 2);
                    $tmp[$i][5] =number_format($result_amountright-$result_amountmandatory_hazor-$result_amountmandatory_amltbnk-$result_amountmandatory_shkd, 2);

                    $start_date->modify('+1 month');
                }
                $array_t5[$city_id]=$tmp;

            }

            //return $array_t5;

            $tables[] = 'array_t1';
            $tables[] = 'array_t2';
            $tables[] = 'array_t3';
            $tables[] = 'array_t4';
            $tables[] = 'array_t5';
        }


        return view('reports.reportfinal', compact('enterprise', $tables))
            ->with(
                [
                    'pageTitle' => "تقارير مؤسسات",
                    'subTitle' => 'بناء تقارير للموسسات',
                ]
            );
    }

    public function mainPageProj(Request $requset)
    {
        //var_dump($requset->fdate);

        //if(!empty($requset->fdate)){
        $reports = array();
        if ($requset->has('fdate')) {
            $enterp_proj = $requset->enterp;
            $fromDate = $requset->fdate;
            $toDate = $requset->tdate;
            $enterp_proj = explode("*", $enterp_proj);
            $enterp = $enterp_proj[0];
            $proj = $enterp_proj[1];
            $city = $requset->city;
            /**
             * $id_bank =1;
             * $fromDate ='2021-01-01';
             * $toDate='2021-12-31';
             */

            $r1 = $this->xreport1($enterp, $fromDate, $toDate);
            $reports[] = 'r1';

            $r2 = $this->xreport2($enterp, $fromDate, $toDate, $type = 0, $proj);
            $reports[] = 'r2';


            $r3 = $this->xreport3($enterp, $fromDate, $toDate, $type = 0, $proj, $city);
            $reports[] = 'r3';


            $r4_out = $this->xreport4($enterp, $fromDate, $toDate, '1', $proj, $city);
            $reports[] = 'r4_out';
            $r5_out = $this->xreport5($enterp, $fromDate, $toDate, '1', $proj, $city);
            $reports[] = 'r5_out';
            $r6_out = $this->xreport6($enterp, $fromDate, $toDate, '1', $proj, $city);
            $reports[] = 'r6_out';
            $r7_out = $this->xreport7($enterp, $fromDate, $toDate, '1', $proj, $city);
            $reports[] = 'r7_out';

            $r4_in = $this->xreport4($enterp, $fromDate, $toDate, '2', $proj, $city);
            $reports[] = 'r4_in';
            $r5_in = $this->xreport5($enterp, $fromDate, $toDate, '2', $proj, $city);
            $reports[] = 'r5_in';
            $r6_in = $this->xreport6($enterp, $fromDate, $toDate, '2', $proj, $city);
            $reports[] = 'r6_in';
            $r7_in = $this->xreport7($enterp, $fromDate, $toDate, '2', $proj, $city);
            $reports[] = 'r7_in';


            //return $r10_out;
        } else {
            $requset->fdate = date('Y-01-01');
            $requset->tdate = date('Y-12-31');
        }

        $enterprise = Enterprise::with(['project'])->get();
        $city = City::get();
        //return $city;
        return view('reports.reportproject', compact('enterprise', 'city', $reports))
            ->with(
                [
                    'pageTitle' => "تقارير بنكيه",
                    'subTitle' => 'بناء تقارير بنكية',
                ]
            );

    }


    function xreport1($enterp, $fromDate, $toDate, $type = 0)
    {
        /*
         * עמותה
         * חובה
         * זכות
         * נטו
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp'
                , DB::raw("round(sum(banksline.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksline.amountright),2) as amountright")
                , DB::raw("round(sum(banksline.amountright-banksline.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->where('id_enter', $enterp)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate)
            ->groupBy('enterprise.name')
            ->orderBy('enterprise.name');

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
        }

        $result = $result->get();
        return json_decode(json_encode($result), true);
        //return $result;
    }

    function xreport2($enterp, $fromDate, $toDate, $type = 0, $proj = 0)
    {
        /*
         * עמותה
         * פרויקט
         * חובה
         * זכות
         * נטו
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp', 'projects.name as proj'
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            );


        $result->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->where('id_enter', $enterp)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate);
        if ($proj != '0') {
            $result->where('projects.id', $proj);
        }

        $result->groupBy('enterprise.name', 'projects.name')
            ->orderBy('enterprise.name')
            ->orderBy('projects.name');

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
        }

        $result = $result->get();
        return json_decode(json_encode($result), true);
        //return $result;
    }

    function xreport3($enterp, $fromDate, $toDate, $type = 0, $proj = 0, $city = 0)
    {
        /*
         * עמותה
         * פרויקט
         * עיר
         * חובה
         * זכות
         * נטו
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp', 'projects.name as proj'
                , 'city.city_name'
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')
            ->where('id_enter', $enterp)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate);
        if ($proj != '0') {
            $result->where('projects.id', $proj);
        }
        if ($city != '0') {
            $result->where('city.city_id', $city);
        }
        $result->groupBy('enterprise.name', 'projects.name', 'city.city_name')
            ->orderBy('enterprise.name')
            ->orderBy('projects.name')
            ->orderBy('city.city_name');

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
        }

        $result = $result->get();
        return json_decode(json_encode($result), true);
        //return $result;
    }

    function xreport4($enterp, $fromDate, $toDate, $type = 0, $proj = 0, $city = 0)
    {
        /*
         * עמותה
         * פרויקט
         * עיר
         * סוג תנועה
         * חובה
         * זכות
         * נטו
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp', 'projects.name as proj'
                , 'city.city_name', 'title_two.ttwo_text'
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')
            ->where('id_enter', $enterp)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate);
        if ($proj != '0') {
            $result->where('projects.id', $proj);
        }
        if ($city != '0') {
            $result->where('city.city_id', $city);
        }

        $result->groupBy('enterprise.name', 'title_two.ttwo_text', 'projects.name', 'city.city_name')
            ->orderBy('enterprise.name')
            ->orderBy('title_two.ttwo_text')
            ->orderBy('projects.name')
            ->orderBy('city.city_name');

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
        }

        $result = $result->get();
        return json_decode(json_encode($result), true);
        //return $result;
    }

    function xreport5($enterp, $fromDate, $toDate, $type = 0, $proj = 0, $city = 0)
    {
        /*
         * עמותה
         * פרויקט
         * עיר
         * סוג תנועה
         * סוג חובה / זכות
         * חובה
         * זכות
         * נטו
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp', 'projects.name as proj'
                , 'city.city_name', 'title_two.ttwo_text'
                //,'expense.name as expensename'
                //,'income.name as incomename'
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')
            ->leftJoin('expense', 'expense.id', '=', 'banksdetail.id_expens')
            ->leftJoin('income', 'income.id', '=', 'banksdetail.id_incom')
            ->where('id_enter', $enterp)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate);

        if ($proj != '0') {
            $result->where('projects.id', $proj);
        }
        if ($city != '0') {
            $result->where('city.city_id', $city);
        }

        $result->groupBy('enterprise.name', 'title_two.ttwo_text', 'projects.name', 'city.city_name')
            //->groupBy('expense.name','income.name')
            ->orderBy('enterprise.name')
            ->orderBy('title_two.ttwo_text')
            ->orderBy('projects.name')
            ->orderBy('city.city_name');
        //->orderBy('expense.name')
        //->orderBy('income.name');

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
            //$result->whereNotNull('expense.name');
            //פירוט רק עבור תשלומים לספקים
            $result->where('banksline.id_titletwo', 2);

            $result->addSelect('expense.name as expensename')
                ->groupBy('expense.name')
                ->orderBy('expense.name');
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
            //$result->whereNotNull('income.name');

            $result->addSelect('income.name as incomename')
                ->groupBy('income.name')
                ->orderBy('income.name');

        }

        $result = $result->get();
        return json_decode(json_encode($result), true);
        //return $result;
    }

    function xreport6($enterp, $fromDate, $toDate, $type = 0, $proj = 0, $city = 0)
    {
        /*
         * עמותה
         * פרויקט
         * עיר
         * סוג תנועה
         * סוג חובה / זכות
         * חודש
         * חובה
         * זכות
         * נטו
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp', 'projects.name as proj'
                , 'city.city_name', 'title_two.ttwo_text'
                //,'expense.name as expensename'
                //,'income.name as incomename'
                , DB::raw("(DATE_FORMAT(datemovement, '%m-%Y')) as month_year")
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')
            ->leftJoin('expense', 'expense.id', '=', 'banksdetail.id_expens')
            ->leftJoin('income', 'income.id', '=', 'banksdetail.id_incom')
            ->where('id_enter', $enterp)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate);

        if ($proj != '0') {
            $result->where('projects.id', $proj);
        }
        if ($city != '0') {
            $result->where('city.city_id', $city);
        }

        $result->groupBy('enterprise.name', 'title_two.ttwo_text', 'projects.name', 'city.city_name')
            //->groupBy('expense.name','income.name')
            ->groupBy(DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"))
            ->orderBy('enterprise.name')
            ->orderBy('title_two.ttwo_text')
            ->orderBy('projects.name')
            ->orderBy('city.city_name')
            //->orderBy('expense.name')
            //->orderBy('income.name')
            ->orderBy(DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"));

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
            //$result->whereNotNull('expense.name');
            //פירוט רק עבור תשלומים לספקים
            $result->where('banksline.id_titletwo', 2);

            $result->addSelect('expense.name as expensename')
                ->groupBy('expense.name')
                ->orderBy('expense.name');
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
            //$result->whereNotNull('income.name');

        }

        $result = $result->get();
        return json_decode(json_encode($result), true);
        //return $result;
    }

    function xreport7($enterp, $fromDate, $toDate, $type = 0, $proj = 0, $city = 0)
    {
        /*
         * עמותה
         * פרויקט
         * עיר
         * קמפיין
         * חובה
         * זכות
         * נטו
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp', 'projects.name as proj'
                , 'city.city_name', 'campaigns.name_camp'

                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')
            ->leftJoin('campaigns', 'campaigns.id', '=', 'banksdetail.id_campn')
            ->where('id_enter', $enterp)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate)
            ->whereNotNull('banksdetail.id_campn');

        if ($proj != '0') {
            $result->where('projects.id', $proj);
        }
        if ($city != '0') {
            $result->where('city.city_id', $city);
        }

        $result->groupBy('enterprise.name', 'projects.name', 'city.city_name', 'campaigns.name_camp')
            ->orderBy('enterprise.name')
            ->orderBy('projects.name')
            ->orderBy('city.city_name')
            ->orderBy('campaigns.name_camp');

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
            //$result->whereNotNull('expense.name');
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
            //$result->whereNotNull('income.name');
        }

        $result = $result->get();
        return json_decode(json_encode($result), true);
        //return $result;
    }


    function xxreport1($enterp, $fromDate, $toDate, $type = 0)
    {
        /*
         * עמותה
         * פרויקט
         * עיר
         * סוג תנועה
         * חובה
         * זכות
         * נטו
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp', 'projects.name as proj'
                , 'city.city_name', 'title_two.ttwo_text'
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')
            ->where('id_enter', $enterp)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate)
            ->groupBy('enterprise.name', 'title_two.ttwo_text', 'projects.name', 'city.city_name')
            ->orderBy('enterprise.name')
            ->orderBy('title_two.ttwo_text')
            ->orderBy('projects.name')
            ->orderBy('city.city_name');

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
        }

        $result = $result->get();

        return $result;
    }


    function report1($enterp, $fromDate, $toDate)
    {

        /**1
         * עמתה
         * מספר שורות
         * סך הכל חובה
         * סל הכל זכות
         * סך הכל
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
            ->where('id_enter', $enterp)
            ->where('datemovement', '>=', $fromDate)
            ->where('datemovement', '<=', $toDate)
            ->groupBy('id_enter')
            ->get();
        return $result;
    }

    function report2($id_bank, $fromDate, $toDate, $type = 0)
    {
        /**2
         * חודש
         * סך הכל חובה
         * סל הכל זכות
         * סך הכל
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
            ->where('datemovement', '>=', $fromDate)
            ->where('datemovement', '<=', $toDate)
            ->groupBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"))
            ->groupBy('id_enter')
            ->orderBy('id_enter')
            ->orderBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"));

        if ($type == 1) {
            // רק חובה
            $result->where('amountmandatory', "!=", 0);
        } elseif ($type == 2) {
            //רק זכות
            $result->where('amountright', "!=", 0);
        }

        $result = $result->get();;
        return $result;
    }

    function report3($id_bank, $fromDate, $toDate, $type = 0)
    {
        /**3
         * סוג תנועה
         * סך הכל חובה
         * סל הכל זכות
         * סך הכל
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
            ->with(['enterprise', 'titletwo'])
            ->where('id_bank', $id_bank)
            ->where('datemovement', '>=', $fromDate)
            ->where('datemovement', '<=', $toDate)
            ->groupBy('id_enter')
            ->groupBy('id_titletwo');

        if ($type == 1) {
            // רק חובה
            $result->where('amountmandatory', "!=", 0);
        } elseif ($type == 2) {
            //רק זכות
            $result->where('amountright', "!=", 0);
        }

        $result = $result->get();;
        return $result;
    }

    function report4($id_bank, $fromDate, $toDate, $type = 0)
    {
        /**4
         * חודש
         * סוג תנועה
         * סך הכל חובה
         * סל הכל זכות
         * סך הכל
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
            ->with(['enterprise', 'titletwo'])
            ->where('id_bank', $id_bank)
            ->where('datemovement', '>=', $fromDate)
            ->where('datemovement', '<=', $toDate)
            ->groupBy('id_enter')
            ->groupBy('id_titletwo')
            ->groupBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"))
            ->orderBy('id_enter')
            ->orderBy('id_titletwo')
            ->orderBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"));

        if ($type == 1) {
            // רק חובה
            $result->where('amountmandatory', "!=", 0);
        } elseif ($type == 2) {
            //רק זכות
            $result->where('amountright', "!=", 0);
        }

        $result = $result->get();

        return $result;
    }

    function report5($id_bank, $fromDate, $toDate, $type = 0)
    {
        /*
         * עמותה
         * סוג תנועה
         * פרויקט
         * חובה
         * זכות
         * נטו
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp', 'title_two.ttwo_text', 'projects.name as proj'
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->where('banksline.id_bank', '=', $id_bank)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate)
            ->groupBy('enterprise.name', 'title_two.ttwo_text', 'projects.name')
            ->orderBy('enterprise.name')
            ->orderBy('title_two.ttwo_text')
            ->orderBy('projects.name');

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
        }

        $result = $result->get();

        return $result;
    }

    function report6($id_bank, $fromDate, $toDate, $type = 0)
    {
        /*
         * עמותה
         * פרויקט
         * עיר
         * סוג תנועה
         * חובה
         * זכות
         * נטו
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp', 'projects.name as proj'
                , 'city.city_name', 'title_two.ttwo_text'
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')
            ->where('banksline.id_bank', '=', $id_bank)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate)
            ->groupBy('enterprise.name', 'title_two.ttwo_text', 'projects.name', 'city.city_name')
            ->orderBy('enterprise.name')
            ->orderBy('title_two.ttwo_text')
            ->orderBy('projects.name')
            ->orderBy('city.city_name');

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
        }

        $result = $result->get();

        return $result;
    }


    function report7($enterp, $fromDate, $toDate, $type = 0)
    {
        /**7
         * עמותה
         * פרויקט
         * סך הכל חובה
         * סל הכל זכות
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp', 'projects.name as proj'
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->where('banksline.id_enter', $enterp)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate)
            ->groupBy('enterprise.name', 'projects.name')
            ->orderBy('enterprise.name')
            ->orderBy('projects.name');

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
        }

        $result = $result->get();

        return $result;
    }

    function report8($enterp, $fromDate, $toDate, $type = 0)
    {
        /**8
         * עמותה
         * פרויקט
         * עיר
         * סך הכל חובה
         * סל הכל זכות
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp', 'projects.name as proj'
                , 'city.city_name'
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')
            ->where('banksline.id_enter', $enterp)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate)
            ->groupBy('enterprise.name', 'projects.name', 'city.city_name')
            ->orderBy('enterprise.name')
            ->orderBy('projects.name')
            ->orderBy('city.city_name');

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
        }

        $result = $result->get();

        return $result;
    }


    function report9($id_bank, $fromDate, $toDate, $type = 0)
    {
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
            ->select('enterprise.name as enterp', 'title_two.ttwo_text', 'projects.name as proj'
                , 'city.city_name'
                , 'expense.name as expensename'
                , 'income.name as incomename'
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')
            ->leftJoin('expense', 'expense.id', '=', 'banksdetail.id_expens')
            ->leftJoin('income', 'income.id', '=', 'banksdetail.id_incom')
            ->where('banksline.id_bank', '=', $id_bank)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate)
            ->groupBy('enterprise.name', 'title_two.ttwo_text', 'projects.name'
                , 'city.city_name', 'expense.name', 'income.name')
            ->orderBy('enterprise.name')
            ->orderBy('title_two.ttwo_text')
            ->orderBy('projects.name')
            ->orderBy('city.city_name')
            ->orderBy('expense.name')
            ->orderBy('income.name');

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
            $result->whereNotNull('expense.name');
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
            $result->whereNotNull('income.name');
        }

        $result = $result->get();

        return $result;
    }

    function report10($id_bank, $fromDate, $toDate, $type = 0)
    {
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
            ->select('enterprise.name as enterp', 'title_two.ttwo_text', 'projects.name as proj'
                , 'city.city_name'
                , 'expense.name as expensename'
                , 'income.name as incomename'
                , \DB::raw("(DATE_FORMAT(datemovement, '%m-%Y')) as month_year")
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')
            ->leftJoin('expense', 'expense.id', '=', 'banksdetail.id_expens')
            ->leftJoin('income', 'income.id', '=', 'banksdetail.id_incom')
            ->where('banksline.id_bank', '=', $id_bank)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate)
            ->groupBy('enterprise.name', 'title_two.ttwo_text', 'projects.name'
                , 'city.city_name', 'expense.name', 'income.name')
            ->groupBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"))
            ->orderBy('enterprise.name')
            ->orderBy('title_two.ttwo_text')
            ->orderBy('projects.name')
            ->orderBy('city.city_name')
            ->orderBy('expense.name')
            ->orderBy('income.name')
            ->orderBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"));

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
            $result->whereNotNull('expense.name');
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
            $result->whereNotNull('income.name');
        }

        $result = $result->get();

        return $result;
    }


    function report11($id_bank, $fromDate, $toDate, $type = 0)
    {
        /*
         * עמותה
         * פרויקט
         * קמפיין
         * חובה/זכות
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp', 'projects.name as proj'
                , 'campaigns.name_camp'
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')
            ->leftJoin('expense', 'expense.id', '=', 'banksdetail.id_expens')
            ->leftJoin('income', 'income.id', '=', 'banksdetail.id_incom')
            ->leftJoin('campaigns', 'campaigns.id', '=', 'banksdetail.id_campn')
            ->where('banksline.id_bank', '=', $id_bank)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate)
            ->whereNotNull('campaigns.name_camp')
            ->groupBy('enterprise.name', 'projects.name'

            )
            ->groupBy('campaigns.name_camp')
            ->orderBy('enterprise.name')
            ->orderBy('projects.name');

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
            $result->whereNotNull('expense.name');
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
            $result->whereNotNull('income.name');
        }

        $result = $result->get();

        return $result;
    }

    function report12($id_bank, $fromDate, $toDate, $type = 0)
    {
        /*
         * עמותה
         * פרויקט
         * חודש
         * קמפיין
         * חובה/זכות
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp', 'projects.name as proj'
                //,'city.city_name'
                , \DB::raw("(DATE_FORMAT(datemovement, '%m-%Y')) as month_year")
                , 'campaigns.name_camp'
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')
            ->leftJoin('expense', 'expense.id', '=', 'banksdetail.id_expens')
            ->leftJoin('income', 'income.id', '=', 'banksdetail.id_incom')
            ->leftJoin('campaigns', 'campaigns.id', '=', 'banksdetail.id_campn')
            ->where('banksline.id_bank', '=', $id_bank)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate)
            ->whereNotNull('campaigns.name_camp')
            ->groupBy('enterprise.name', 'projects.name'
            )
            ->groupBy('campaigns.name_camp')
            ->groupBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"))
            ->orderBy('enterprise.name')
            ->orderBy('projects.name')
            ->orderBy(\DB::raw("(DATE_FORMAT(datemovement, '%m-%Y'))"));

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
            $result->whereNotNull('expense.name');
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
            $result->whereNotNull('income.name');
        }

        $result = $result->get();

        return $result;
    }

    function report13($id_bank, $fromDate, $toDate, $type = 0)
    {
        /*
         * עמותה
         * פרויקט
         * עיר
         * קמפיין
         * חובה/זכות
         */
        $result = DB::table('banksline')
            ->select('enterprise.name as enterp', 'projects.name as proj'
                , 'city.city_name'
                , 'campaigns.name_camp'
                , DB::raw("round(sum(banksdetail.amountmandatory),2) as amountmandatory")
                , DB::raw("round(sum(banksdetail.amountright),2) as amountright")
                , DB::raw("round(sum(banksdetail.amountright-banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('enterprise', 'enterprise.id', '=', 'banksline.id_enter')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'banksline.id_titletwo')
            ->leftJoin('banksdetail', 'banksdetail.id_line', '=', 'banksline.id_line')
            ->leftJoin('projects', 'projects.id', '=', 'banksdetail.id_proj')
            ->leftJoin('city', 'city.city_id', '=', 'banksdetail.id_city')
            ->leftJoin('expense', 'expense.id', '=', 'banksdetail.id_expens')
            ->leftJoin('income', 'income.id', '=', 'banksdetail.id_incom')
            ->leftJoin('campaigns', 'campaigns.id', '=', 'banksdetail.id_campn')
            ->where('banksline.id_bank', '=', $id_bank)
            ->where('banksline.datemovement', '>=', $fromDate)
            ->where('banksline.datemovement', '<=', $toDate)
            ->whereNotNull('campaigns.name_camp')
            ->groupBy('enterprise.name', 'projects.name'
                , 'city.city_name'
            )
            ->groupBy('campaigns.name_camp')
            ->orderBy('enterprise.name')
            ->orderBy('projects.name')
            ->orderBy('city.city_name');

        if ($type == 1) {
            // רק חובה
            $result->where('banksline.amountmandatory', "!=", 0);
            $result->whereNotNull('expense.name');
        } elseif ($type == 2) {
            //רק זכות
            $result->where('banksline.amountright', "!=", 0);
            $result->whereNotNull('income.name');
        }

        $result = $result->get();

        return $result;
    }
}
