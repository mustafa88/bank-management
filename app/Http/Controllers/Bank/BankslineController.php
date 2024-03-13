<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\bank\BankslineRequset;
use App\Models\Bank\Banks;
use App\Models\Bank\Banksdetail;
use App\Models\Bank\Banksline;
use App\Models\Bank\Enterprise;
use App\Models\Bank\Title_one;
use App\Traits\BankslineTrait;
use App\Traits\HelpersTrait;
use Illuminate\Http\Request;

class BankslineController extends Controller
{
    use BankslineTrait,HelpersTrait;

    public function showTable(Request $request ,$id_bank )
    {
        /*
         * ,$fromDate=null ,$toDate=null
        if($fromDate!=null and $toDate!=null){
            $request->session()->put('showLineBankFromDate',$fromDate);
            $request->session()->put('showLineBankToDate',$toDate);
        }
        **/
        if($request->fromDate!=null and $request->toDate!=null){
            $request->session()->put('showLineBankFromDate',$request->fromDate);
            $request->session()->put('showLineBankToDate',$request->toDate);
        }

        if($request->showTitleTwo!=null){
            $request->session()->put('showLineBankTitleTwo',$request->showTitleTwo);
        }

        if(!$request->session()->has('showLineBankFromDate')){
            $request->session()->put('showLineBankFromDate',date('Y-01-01'));
        }

        if(!$request->session()->has('showLineBankToDate')){
            $request->session()->put('showLineBankToDate',date('Y-12-31'));
        }
        if(!$request->session()->has('showLineBankTitleTwo')){
            $request->session()->put('showLineBankTitleTwo','0');
        }

        $showLineBankFromDate = $request->session()->get('showLineBankFromDate');
        $showLineBankToDate = $request->session()->get('showLineBankToDate');
        $showLineBankTitleTwo = $request->session()->get('showLineBankTitleTwo');
        //echo $showLineBankFromDate . " - " .$showLineBankToDate;

        $banksline = Banksline::with(['banks', 'titletwo', 'enterprise'])
            ->where('id_bank', '=', $id_bank)
            //->where('datemovement', '>=', '2022-01-01')
            //->where('datemovement', '<=', '2022-12-31')
            ->where('datemovement', '>=', $showLineBankFromDate)
            ->where('datemovement', '<=', $showLineBankToDate);
        if($showLineBankTitleTwo!='0'){
            $banksline->where('id_titletwo', '=', $showLineBankTitleTwo);
        }
        $banksline =   $banksline->get();
        //return $banksline;
        $bank = banks::with(['enterprise', 'projects'])->find($id_bank);
        //return $bank;
        $enterprise = Enterprise::get();
        $title = Title_one::with(['titleTwo'])->get()->toArray();
        return view('manageabnk.linebanks', compact('banksline', 'bank', 'enterprise', 'title'))
            ->with(
                [
                    'pageTitle' => "תנועות חשבון בבנק",
                    'subTitle' => 'פירוט תנועות חשבון בבנק',
                ]
            );
    }

    /**
     * @param $id_bank מספר בנק
     * שורות בנק ללא סוג שורה
     */
    public function showTableNoTypeLine(Request $request ,$id_bank){

        $showLineBankTitleTwo = 0;
        if($request->showLineBankTitleTwo!=null){
            $showLineBankTitleTwo = $request->showLineBankTitleTwo;
        }

        $banksline = Banksline::with(['banks', 'titletwo', 'enterprise'])
            ->where('id_bank', '=', $id_bank)
        ->whereNull('id_titletwo');
        $banksline =   $banksline->get();

        $banksline_a = array();
        for($i=0;$i<count($banksline);$i++){
            $description = $banksline[$i]['description'];
            $posstr = strpos($description,"תאריך ערך");


            $resChk = \DB::table('Banksline')
                ->select('id_titletwo', \DB::raw('count(*) as total_idtwo'))
                ->where('id_bank', '=', $id_bank)
                ->where('description', '=', $description)
                ->whereNotNull('id_titletwo')
                ->groupBy('id_titletwo')
                ->orderBy(\DB::raw('count(*)'),'desc')
                ->get();

            $resChk = json_decode($resChk, true);

            if(!isset($resChk[0]['total_idtwo'])  and $posstr!==false){
                //ddd($resChk);
                $description = substr($description,0,$posstr-1);

                $resChk = \DB::table('Banksline')
                    ->select('id_titletwo', \DB::raw('count(*) as total_idtwo'))
                    ->where('id_bank', '=', $id_bank)
                    ->where('description', 'LIKE', '%'.$description.'%')
                    ->whereNotNull('id_titletwo')
                    ->groupBy('id_titletwo')
                    ->orderBy(\DB::raw('count(*)'),'desc')
                    ->get();
                $resChk = json_decode($resChk, true);
            }



            $ofer_title_two = 0;
            if(isset($resChk[0]['total_idtwo']) and $resChk[0]['total_idtwo']>1){
                $ofer_title_two = $resChk[0]['id_titletwo'];
            }
            $banksline[$i]['ofer_title_two']= $ofer_title_two;

            if($showLineBankTitleTwo!='0' and $ofer_title_two!=$showLineBankTitleTwo){
                continue;
            }
            $banksline_a[]=$banksline[$i];
        }
        $banksline = $banksline_a;

        //return $banksline;
        $bank = banks::with(['enterprise', 'projects'])->find($id_bank);
        //return $bank;
        $title = Title_one::with(['titleTwo'])->get()->toArray();

        return view('manageabnk.linebanksnotype', compact('banksline', 'bank','title'))
            ->with(
                [
                    'pageTitle' => "תנועות בנק ללא סוג",
                    'subTitle' => 'תנועות בנק בהמתנה למיון לפי סוג',
                ]
            );
    }


    public function editAjax($id_bank, $id_line)
    {

        $daterow = Banksline::with(['banks', 'titletwo', 'enterprise'])->where('id_bank', '=', $id_bank)->find($id_line);
        if (!$daterow) {
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'תקלה - שורה לא קיימת';
            return response()->json($resultArr);
        }
        $resultArr['status'] = true;
        $resultArr['cls'] = 'info';
        $resultArr['msg'] = 'עריכת שורה';
        $resultArr['row'] = $daterow;
        return response()->json($resultArr);
    }

    public function storeAjax(BankslineRequset $requset, $id_bank)
    {

        if ($requset->amountmandatory == 0 and $requset->amountright == 0) {
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'לא ניתן לשמור סכום חובה וזכות שווים לאפס';
            return response()->json($resultArr);
        }

         $nobank =0;
        if((isset($requset->nobank) and $requset->nobank=='1')){
             $nobank = 1;
        }
        $arrDate = [
            'id_bank' => $id_bank,
            'datemovement' => $requset->datemovement,//תארך תנועה
            'datevalue' => $requset->datemovement,//תאריך ערך
            'description' => $requset->description,
            'note' => $requset->note,
            'asmcta' => $requset->asmcta,
            'amountmandatory' => $requset->amountmandatory,
            'amountright' => $requset->amountright,
            'id_titletwo' => $requset->id_titletwo,
            'id_enter' => $requset->id_enter,
            'duplicate' => 0,
            'nobank' => $nobank,
            'done' => 0,
        ];

        $rowinsert = Banksline::create($arrDate);

        $this->checkIfDuplicateLine($rowinsert);

        $this->checkFixLine($rowinsert->id_line);

        $rowBanksLine = Banksline::with(['banks', 'titletwo', 'enterprise'])->where('id_bank', '=', $id_bank)->find($rowinsert->id_line);


        $rowHtml = view('layout.includes.linedetail_displayrowl',compact('rowBanksLine'))->render();

        $resultArr['status'] = true;
        $resultArr['cls'] = 'success';
        $resultArr['msg'] = 'تم الحفظ بنجاح';
        $resultArr['row'] = $rowBanksLine;

        $resultArr['rowHtml'] = $rowHtml;
        return response()->json($resultArr);
    }


    public function updateAjax(BankslineRequset $requset, $id_bank, $id_line)
    {

        $daterow = Banksline::with(['banks', 'titletwo', 'enterprise'])->where('id_bank', '=', $id_bank)->find($id_line);
        if (!$daterow) {
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'תקלה - שורה לא קיימת';
            return response()->json($resultArr);
        }
        $old_id_titletwo = $daterow->id_titletwo;
        $old_id_enter = $daterow->id_enter;

        $done = 0;

        $nobank =0;
        if((isset($requset->nobank) and $requset->nobank=='1')){
            $nobank = 1;
        }



        $daterow->datemovement = $requset->datemovement;
        $daterow->datevalue = $requset->datemovement;
        $daterow->description = $requset->description;
        $daterow->note = $requset->note;
        $daterow->asmcta = $requset->asmcta;
        $daterow->amountmandatory = $requset->amountmandatory;
        $daterow->amountright = $requset->amountright;
        $daterow->id_titletwo = $requset->id_titletwo;
        $daterow->id_enter = $requset->id_enter;
        $daterow->duplicate = 0;
        $daterow->nobank = $nobank;
        $daterow->done = $done;



        $daterow->save();

        if($old_id_titletwo!=$requset->id_titletwo or $old_id_enter!=$requset->id_enter){
            //אם השתנה סוג תנועה או עמותה מוחקים פירוט השורה במידה וקיים
            Banksdetail::where('id_line', '=', $id_line)->delete();
        }

        $rowBanksLine = Banksline::where('id_bank', '=', $id_bank)->find($id_line);
        //חלוקת שורה בין כל הארגונים במידה והיא מסוג עמלת בנק
        $this->storeDivDetail($id_line);
        $this->checkIfDuplicateLine($rowBanksLine);
        $this->checkFixLine($id_line);

        $rowBanksLine = Banksline::with(['banks', 'titletwo', 'enterprise'])->where('id_bank', '=', $id_bank)->find($id_line);

        $rowHtml = view('layout.includes.linedetail_displayrowl',compact('rowBanksLine'))->render();

        $resultArr['status'] = true;
        $resultArr['cls'] = 'success';
        $resultArr['msg'] = 'تم التعديل بنجاح';
        $resultArr['row'] = $rowBanksLine;
        $resultArr['rowHtml'] = $rowHtml;
        $resultArr['rowHtmlArr'] = $this->rowHtmlToArray($rowHtml);

        return response()->json($resultArr);

    }

    /**
     * @param $id_bank
     * @param $id_line
     * @return \Illuminate\Http\JsonResponse
     * מחיקת שורה
     */
    public function deleteAjax($id_bank, $id_line)
    {
        $daterow = Banksline::with(['banks', 'titletwo', 'enterprise'])->where('id_bank', '=', $id_bank)->find($id_line);
        if (!$daterow) {
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'תקלה - שורה לא קיימת';
            return response()->json($resultArr);
        }

        Banksdetail::where('id_line','=',$id_line)->delete();

        $daterow->delete();

        $resultArr['status'] = true;
        $resultArr['cls'] = 'success';
        $resultArr['msg'] = 'تم الحذف بنجاح';
        return response()->json($resultArr);

    }

    /**
     * @param $id_bank
     * @param $id_line
     * אישור שורה לא כפולה
     */
    public function noduplicateAjax($id_bank, $id_line)
    {
        $daterow = Banksline::where('id_bank', '=', $id_bank)->find($id_line);
        if (!$daterow) {
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'תקלה - שורה לא קיימת';
            return response()->json($resultArr);
        }

        $daterow->duplicate = 0;
        $daterow->save();

        $resultArr['status'] = true;
        $resultArr['cls'] = 'success';
        $resultArr['msg'] = 'נשמר בהצלחה';
        return response()->json($resultArr);

    }


    /*
     * עדכון גורף לשורות
     * עדכון סוג סורה או עמותה או חלוקת שורה בין כל הארגונים
     */
    public function storeSelect(Request $requset, $id_bank)
    {
        //לא בשימוש - כפתורים שמבעצים פעולה זו נסגרו ועברו למוקם אחר
        $selectbox = $requset->selectbox;
        if(isset($requset->btn_savetitle)){
            //עדכון גורף לסוג תנועה
            Banksline::where('id_bank', '=', $id_bank)
                ->whereIn('id_line', $selectbox)
                ->update(['id_titletwo' => $requset->idselect_titletwo]);

        }elseif ($requset->btn_saveenter){
            //עדכון גורף לעמותה
            Banksline::where('id_bank', '=', $id_bank)
                ->whereIn('id_line', $selectbox)
                ->update(['id_enter' => $requset->idselect_enter]);
        }else{
            //echo $selectbox[0];
            foreach ($selectbox as $v_id_line){
                //חלוקה שווה לשורה בין כל הארגונים
                //בדרך כלל רק לעמולת בנק
                $this->storeDivDetail($v_id_line);
            }
            //ddd($selectbox);
        }
        $countLine = count($selectbox);
        return redirect()->back()->with("successupdateselect", "تم تعديل على {$countLine} سطر");
        //->header('Cache-Control', 'no-store, no-cache, must-revalidate')
    }



    /**
     * @param $id_line
     * מצביע חלוקה שווה לשורה בין כל הפרויקטים המשתתפים
     * חלוקת שורה בין כל הארגונים
     * שימוש לעמלות בנק
     */
    public function storeDivDetail($id_line)
    {
        $bankslin = Banksline::with([
            'banks',
            'titletwo',
            'enterprise.project',
            'enterprise.project.city',
            'banksdetail',
            'banksdetail.projects',
            'banksdetail.city',
            'banksdetail.income',
            'banksdetail.expense',
        ])->find($id_line);

        if($bankslin['id_titletwo']=='2' ){
            //הוצאה מסוג תשלום לספק חייב  שיבחר שם ספק מרשימה לא ניתן שיהיה ריק
            //ddd('error id_titletwo!=2');
        }

        if($bankslin['id_titletwo']!='1' ){
            return false;
        }
        $project = $bankslin['enterprise']['project'];

        $countDiv=0; //כמות פרויקטים וערים משתתפים או שייכים
        $projectCity = array();
        foreach ($project as $item){
            //$projectCity[$item['id']]['name']=$item['name'];
            $projectCity[$item['id']]=array();
            foreach ($item['city'] as $item2){
                $projectCity[$item['id']][$item2['city_id']]='x';
                $countDiv++;
            }
        }
        $scumline = $bankslin['amountmandatory'] + $bankslin['amountright'];

        if($countDiv==0 or $scumline==0){
            return false;
        }
        $sumDiv = round($scumline/$countDiv,2);

        //שארית חלוקה - מקבל פרויקט אחרון
        $sumDivMod = round($scumline - ($sumDiv * ($countDiv-1)),2);

        foreach ($projectCity as $id_proj => $city){
            foreach ($city as $id_city => $value){
                $projectCity[$id_proj][$id_city]=$sumDiv;
            }
        }

        foreach ($projectCity as $id_proj => $city){
            foreach ($city as $id_city => $value){
                $projectCity[$id_proj][$id_city]=$sumDivMod;
                break;
            }
            break;
        }


        //$banksdetail = Banksdetail::where('id_line', '=', $id_line)->get();
        //return $banksdetail;
        //if($banksdetail){
            //מחיקת כל השורות במידה וקקים
            //$banksdetail->delete();
            //\DB::table('Banksdetail')->where('id_line', '=', $id_line)->delete();
            Banksdetail::where('id_line', '=', $id_line)->delete();
        //}

        $arrDate = [
            'id_line' => $id_line,
        ];

        if($bankslin['amountmandatory']==0){
            // זכות - סוג הכנסה
            $arrDate['amountmandatory']=0;
        }else{
            // חובה וסוג הוצאה
            $arrDate['amountright']=0;
        }

        foreach ($projectCity as $id_proj => $city){
            foreach ($city as $id_city => $value){
                $arrDate['id_proj']=$id_proj;
                $arrDate['id_city']=$id_city;
                if($bankslin['amountmandatory']==0){
                    //זכות - סוג הכנסה
                    $arrDate['amountright']=$value;
                }else{
                    //חובה וסוג הוצאה
                    $arrDate['amountmandatory']=$value;
                }
                //RETURN $arrDate;
                Banksdetail::create($arrDate);
            }
        }

        $this->checkFixLine($id_line);
        return true;

    }

    /**
     * @param Request $request
     * @param $id_bank מספר בנק
     * שמירת סוגי שורות בנק
     */
    public function storeTypeLine(Request $request ,$id_bank)
    {
        $selectTitleTwo = $request->selectTitleTwo;

        for ($i=0; $i<count($selectTitleTwo);$i++){
            $select = explode("*", $selectTitleTwo[$i]);
            $id_line = $select[0];
            $typeTitleTwo = $select[1];

            //dd($typeTitleTwo);
            //continue;
            //delete details line
            Banksdetail::where('id_line', '=', $id_line)->delete();


            if($typeTitleTwo==0){
                $typeTitleTwo=NULL;
            }
            Banksline::where('id_bank', '=', $id_bank)->where('id_line', '=', $id_line)->update([
                'id_titletwo' => $typeTitleTwo]);

            if($typeTitleTwo=='1'){
                //עמלת בנק
                //חלוקת שורה עמלת בנק בין כל הארגונים בעמותה
                $this->storeDivDetail($id_line);
            }


        }

        return redirect()->back()->with("success", "تم الحفظ بنجاح");
    }

    /**
     * @param $id_bank
     * @param $id_line
     * @return \Illuminate\Http\JsonResponse
     * מחזיר שורה HTML לחלוקת פירוט שורה ראשית
     */
    public function showrowdetilshtml($id_bank, $id_line)
    {
        //$id_line = 747;
        $bankslin = Banksline::with([
            'banks',
            'titletwo',
            'enterprise.project',
            'enterprise.project.city',
            'banksdetail',
            'banksdetail.projects',
            'banksdetail.city',
            'banksdetail.income',
            'banksdetail.expense',
            'banksdetail.campaigns',
        ])->find($id_line);
        $fullscome = '1';
        $resultArr['status'] = true;
        $resultArr['cls'] = 'success';
        $resultArr['clss'] = $id_bank;
        $resultArr['clssg'] = $id_line;
        $resultArr['html'] = view('layout.includes.linedetailedit',compact('bankslin','fullscome'))->render();
        return response()->json($resultArr);

    }
}
