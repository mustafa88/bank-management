<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank\Banks;
use App\Models\Bank\City;
use App\Models\Bank\Banksdetail;
use App\Models\Bank\Banksline;
use App\Models\Bank\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{


    /**
     * @param $year
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * מצבשוורת לכל בנק -
     * כמה שורות יש בכל חודש וכמה מהם לא תקין
     */
    public function bankLines($year = null)
    {

        if ($year == null) {
            $year = date('Y');
        }
        $banks = Banks::with('enterprise', 'projects')->get();
        //return $banks;
        foreach ($banks as $indexbank => $item) {

            $arrProg[0] = ['חודש', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            $arrProg[1] = ['מס שורות', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-'];
            $arrProg[2] = ['שורות לא תקינות', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-'];


            $id_bank = $item['id_bank'];

            $countAllRow = DB::table('banksline')
                ->select(
                    \DB::raw("(DATE_FORMAT(datemovement, '%m')) as month_year")
                    , DB::raw("count(*)  as countLine")
                )
                ->where('id_bank', '=', $id_bank)
                ->whereYear('datemovement', '=', $year)
                ->groupBy(\DB::raw("(DATE_FORMAT(datemovement, '%m'))"))
                ->orderBy(\DB::raw("(DATE_FORMAT(datemovement, '%m'))"))
                ->get();
            $countAllRow = json_decode($countAllRow, true);

            $countNoFixRow = DB::table('banksline')
                ->select(
                    \DB::raw("(DATE_FORMAT(datemovement, '%m')) as month_year")
                    , DB::raw("count(*)  as countLine")
                )
                ->where('id_bank', '=', $id_bank)
                ->whereYear('datemovement', '=', $year)
                ->whereYear('done', '!=', 1)
                ->groupBy(\DB::raw("(DATE_FORMAT(datemovement, '%m'))"))
                ->orderBy(\DB::raw("(DATE_FORMAT(datemovement, '%m'))"))
                ->get();
            $countNoFixRow = json_decode($countNoFixRow, true);


            foreach ($countAllRow as $index => $item) {
                $arrProg[1][(int)$item['month_year']] = $item['countLine'];
            }

            foreach ($countNoFixRow as $index => $item) {
                $arrProg[2][(int)$item['month_year']] = $item['countLine'];
            }
            if ($id_bank == 2) {
                //ddd($arrProg);
                //return $index;
            }
            //return $index;
            $banks[$indexbank]['arrProg'] = $arrProg;
            unset($arrProg);
        }

        //return $banks;

        $arrYear = array();
        for ($i = 2021; $i <= date('Y'); $i++) {
            $arrYear[] = $i;
        }
        return view('dashboard.banklines', compact('arrYear', 'year', 'banks'))
            ->with(
                [
                    'pageTitle' => "ملخص التقدم السنوي",
                    'subTitle' => 'ملخص التقدم السنوي',
                ]
            );
    }

    /**
     * @param $project
     * @param $year
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function balance(Request $requset ,$project)
    {

        //20/09 - להמשייייייייייייייייך
        /**
         * בכל שנה להצעיג לכל חודש
         * סך הכנסות
         * סך הוצאות
         * ויתרה סופית לפרויקט
         */
        /**مشاريع
         *
         **/
        /**
         * بلدان
         * 1 - عام
         * 2- الطيبة
         */

        $year = $requset->year;


        $projects = Projects::with('city')->find($project);


        $allcity =  json_decode(json_encode ( $projects['city'] ) , true);

        //עיר ראשונה = -1
        //כדי להביא כל הניתונים של כל הערים
        array_unshift($allcity,['city_id'=>-1,'city_name'=>'جميع البلدان']);

        $arrProg[0] = ['חודש', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $arrProg[1] = ['זכות', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-'];
        $arrProg[2] = ['חובה', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-'];
        $arrProg[3] = ['נטו לחודש', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-'];

        $resultProgram = [];

        foreach ($allcity as $item_city) {

            $val_city = $item_city['city_id'];
            $val = $this->balanceQuery($project ,$year ,$val_city);
            $resultProgram[$val_city]['arrProg']  = $arrProg;

            $resultProgram[$val_city]['city']  = $item_city;
            $resultProgram[$val_city]['ytraopen'] = 0;
            $resultProgram[$val_city]['ytraclose'] = 0;

            foreach ($val as $index => $item) {
                $resultProgram[$val_city]['arrProg'][1][(int)$item['month_year']] = $item['amountright'];
                $resultProgram[$val_city]['arrProg'][2][(int)$item['month_year']] = $item['amountmandatory'];
                $resultProgram[$val_city]['arrProg'][3][(int)$item['month_year']] = $item['total_neto'];
            }
            $resultopenclose = $this->balanceOpenClose($project ,$year ,$val_city);

            $resultProgram[$val_city]['ytraopen'] = $resultopenclose['ytraopen'];

            $resultProgram[$val_city]['ytraclose'] = $resultopenclose['ytraclose'];

        }


         //return $resultProgram;

        //
        $arrYear = array();
        for ($i = 2021; $i <= date('Y'); $i++) {
            $arrYear[] = $i;
        }
        return view('dashboard.balance-project', compact('arrYear', 'year', 'resultProgram'))
            ->with(
                [
                    'pageTitle' => "יתרה לפי חודש",
                    'subTitle' => 'הכנסה והוצאה לפי חודש',
                ]
            );

    }

    private function balanceOpenClose($project ,$year ,$city){
        //יתרת פתיחה
        $resultQuery_yetraOpen = DB::table('Banksdetail')
            ->select(
                DB::raw("round(sum(Banksdetail.amountright-Banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('banksline', 'banksline.id_line', '=', 'Banksdetail.id_line')
            ->whereYear('banksline.datemovement', '<', $year)
            ->where('Banksdetail.id_proj', $project);
        if($city!=-1) {
            $resultQuery_yetraOpen->where('Banksdetail.id_city', $city);
        }

        $resultQuery_yetraOpen = $resultQuery_yetraOpen->get();

        $result['ytraopen'] =  json_decode($resultQuery_yetraOpen, true)[0]['total_neto'];

        //יתרת סגירה
        $resultQuery_yetraClose = DB::table('Banksdetail')
            ->select(
                DB::raw("round(sum(Banksdetail.amountright-Banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('banksline', 'banksline.id_line', '=', 'Banksdetail.id_line')
            ->whereYear('banksline.datemovement', '<=', $year)
            ->where('Banksdetail.id_proj', $project);
        if($city!=-1) {
            $resultQuery_yetraOpen->where('Banksdetail.id_city', $city);
        }
        $resultQuery_yetraClose = $resultQuery_yetraClose->get();

        $result['ytraclose'] =  json_decode($resultQuery_yetraClose, true)[0]['total_neto'];

        return $result;

    }
    private function balanceQuery($project, $year , $city = null)
    {

        $resultQuery = DB::table('Banksdetail')
            ->select(
                DB::raw("(DATE_FORMAT(banksline.datemovement, '%m-%Y')) as month_year"),
                DB::raw('SUM(Banksdetail.amountmandatory) as amountmandatory'),
                DB::raw('SUM(Banksdetail.amountright) as amountright'),
                DB::raw("round(sum(Banksdetail.amountright-Banksdetail.amountmandatory),2) as total_neto")
            )
            ->leftJoin('banksline', 'banksline.id_line', '=', 'Banksdetail.id_line')
            ->where('Banksdetail.id_proj', $project);
        if ($city != -1) {
            $resultQuery->where('Banksdetail.id_city', $city);
        }

        $resultQuery->whereYear('banksline.datemovement', '=', $year)
            ->groupBy(DB::raw("(DATE_FORMAT(banksline.datemovement, '%m-%Y'))"));


        return json_decode($resultQuery->get(), true);


    }

}
