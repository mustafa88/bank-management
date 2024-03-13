<?php

namespace App\Traits;


use App\Models\bank\Banksdetail;
use App\Models\bank\Banksline;
use App\Models\bank\Enterprise;
use App\Models\bank\Expense;
use App\Models\bank\Income;
use App\Models\bank\Projects;

trait BankslineTrait
{
    /**
     * @param $id_line
     * עדכון שדה שורה תקינה בכותרת
     * שדה done=1 לכותרת
     */
    function checkFixLine($id_line)
    {
        $banksdetail_sum = banksdetail::select(
            \DB::raw("SUM(amountmandatory) as sum_amountmandatory"),
            \DB::raw("SUM(amountright) as sum_amountright")
        )
            ->where('id_line', $id_line)
            ->get()
            ->first();

        $banksline = Banksline::find($id_line);

        if(round($banksdetail_sum['sum_amountmandatory']-$banksline['amountmandatory'],2)==0
            and
            round($banksdetail_sum['sum_amountright']-$banksline['amountright'],2)==0
        ){
            $banksline->done=1;
        }
        else{
            $banksline->done=0;
        }

        $banksline->save();
    }

    /**
     * @param $id_bank מספר בנק
     * @param $id_line מספר שורה
     * @param $datemovement תאריך תנועה
     * @param $asmcta אסמכתא
     * @param $amountmandatory חובה
     * @param $amountright זכות
     * @return int =1 אם השורה כפול
     * בדיקת אם השורה כפול ומעדכן שורה במידה וכן
     */
    public function checkIfDuplicateLine($rowLineBank)
    {
        $chekDulpict = Banksline
            ::where('id_bank', $rowLineBank->id_bank)
            ->where('id_line', "!=", $rowLineBank->id_line)
            ->where('datemovement', $rowLineBank->datemovement)
            ->where('asmcta', $rowLineBank->asmcta)
            ->where('amountmandatory', $rowLineBank->amountmandatory)
            ->where('amountright', $rowLineBank->amountright)->first();

        if ($chekDulpict) {
            //חדש לשורה כפולה
            //$duplicate = 1;

            $dataRow = Banksline::find($rowLineBank->id_line);
            $dataRow->duplicate = '1';
            $dataRow->save();

            return true;
        }
        return false;
    }

    /**
     * @param $enterp
     * להחזיר רשימת ספקים משופתים לכל הארגון
     */
    function GetSuppressShareEnterprise($id_line){
        $bankslin = Banksline::find($id_line);
        //return ($bankslin);
        $enterp = $bankslin['id_enter']; //מספר ארגון
        $enterprise=Enterprise::with(['project'])->find($enterp);
        $project = $enterprise['project'];
        //return $enterprise;
        $tmp = $project[0]['id'];
        //return $tmp;
        if($bankslin['amountmandatory']!=0){
            //echo 'aa';
            //חובה - להציג ספקים
            $suppress_income = Expense::whereHas('projects', function ($query)use ($tmp) {
                $query->where('projects.id', '=', $tmp);}
            );
            for ($i=1;$i<count($project)-1;$i++){
                $tmp = $project[$i]['id'];
                $suppress_income->whereHas('projects', function ($query) use ($tmp) {
                    $query->where('projects.id', '=', $tmp );
                }
                );
            }

        }else{
            //echo 'bbb';
            //זכות - סוג זכות למשל תרומה וכו
            $suppress_income = Income::whereHas('projects', function ($query)use ($tmp) {
                $query->where('projects.id', '=', $tmp);}
            );
            for ($i=1;$i<count($project)-1;$i++){
                $tmp = $project[$i]['id'];
                $suppress_income->whereHas('projects', function ($query) use ($tmp) {
                    $query->where('projects.id', '=', $tmp );
                }
                );
            }
        }

        return($suppress_income->get());


    }
}
