<?php

namespace App\Http\Controllers\Usb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Usb\UsbIncomeRequest;
use App\Models\Usb\Usbexpense;
use App\Models\bank\City;
use App\Models\bank\Currency;
use App\Models\bank\Enterprise;
use App\Models\bank\Income;
use App\Models\bank\Projects;
use App\Models\Bank\Title_two;
use App\Models\Usb\Usbincome;
use App\Traits\HelpersTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class UsbIncomeController extends Controller
{

    use HelpersTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request, $id_entrep, $id_proj, $id_city)
    {

        if ($request->fromDate != null and $request->toDate != null) {
            $request->session()->put('showLineFromDate', $request->fromDate);
            $request->session()->put('showLineToDate', $request->toDate);
        }

        if (!$request->session()->has('showLineFromDate')) {
            $request->session()->put('showLineFromDate', date('Y-01-01'));
        }

        if (!$request->session()->has('showLineToDate')) {
            $request->session()->put('showLineToDate', date('Y-12-31'));
        }

        $showLineFromDate = $request->session()->get('showLineFromDate');
        $showLineToDate = $request->session()->get('showLineToDate');

        $a_title = Enterprise::find($id_entrep)->name . " => ";
        $a_title .= Projects::find($id_proj)->name . " => ";
        $a_title .= City::find($id_city)->city_name;

        $currency = Currency::get();

        $income = Income::whereHas('projects', function ($q) use ($id_proj) {
            $q->where('projects.id', $id_proj)->where('inactive', '0');

        })->get();

        $title_two = Title_two::Where('ttwo_one_id', 1)->get();


        $usbincome = Usbincome::with(['enterprise', 'projects', 'city', 'income', 'currency', 'titletwo'])
            ->where('dateincome', '>=', $showLineFromDate)
            ->where('dateincome', '<=', $showLineToDate)
            ->where('id_enter', $id_entrep)
            ->where('id_proj', $id_proj)
            ->where('id_city', $id_city)
            ->get();

        //return $usb;


        $param_url = ['id_entrep' => $id_entrep, 'id_proj' => $id_proj, 'id_city' => $id_city];

        $dataTables = 'v1';
        return view('usb.income',
            //compact('enterprise','city','donatetype','donateworth')
            compact('usbincome', 'currency', 'income', 'title_two', 'param_url', 'dataTables')
        )
            ->with(
                [
                    'pageTitle' => "سجل مدخولات {$a_title}",
                    'subTitle' => 'سجل المدخولات للمشروع',
                ]
            );
    }


    public function index_entrep(Request $request, $id_entrep, $id_city)
    {
        if ($request->fromDate != null and $request->toDate != null) {
            $request->session()->put('showLineFromDate', $request->fromDate);
            $request->session()->put('showLineToDate', $request->toDate);
        }

        if (!$request->session()->has('showLineFromDate')) {
            $request->session()->put('showLineFromDate', date('Y-m-d'));
        }

        if (!$request->session()->has('showLineToDate')) {
            $request->session()->put('showLineToDate', date('Y-m-d'));
        }

        $chekZka = substr($request->user()->username,-3) =='zka' ? 1:0 ;

        $lastDayDate = (new \DateTime())->modify('-1 day')->format('Y-m-d');

        if($chekZka &&  $request->session()->get('showLineFromDate') <$lastDayDate){
            //משתממש ZKA לא יכול לחזור בתאריכים יותר מיום אחד
            $request->session()->put('showLineFromDate', date('Y-m-d'));
        }


        $showLineFromDate = $request->session()->get('showLineFromDate');
        $showLineToDate = $request->session()->get('showLineToDate');

        $a_title = Enterprise::find($id_entrep)->name . " => ";
        $a_title .= City::find($id_city)->city_name;

        $currency = Currency::get();


        $projects = Projects::whereHas('city', function ($q) use ($id_city) {
            $q->where('city.city_id', $id_city);
        })->whereHas('enterprise', function ($q) use ($id_entrep) {
            $q->where('enterprise.id', $id_entrep);
        })
            ->get();

        //return $projects;
        /**
         * $income = Income::whereHas('projects', function($q) use ($id_proj){
         * $q->where('projects.id', $id_proj)->where('inactive','0');
         *
         * })->get();
         **/
        $title_two = Title_two::Where('ttwo_one_id', 1)->get();

        //return $showLineFromDate;
        $usbincome = Usbincome::with(['enterprise', 'projects', 'city', 'income', 'currency', 'titletwo'])
            ->where('dateincome', '>=', $showLineFromDate)
            ->where('dateincome', '<=', $showLineToDate)
            ->where('id_enter', $id_entrep)
            //->where('id_proj',$id_proj)
            ->where('id_city', $id_city)
            ->get();

        //return $usbincome;


        $param_url = ['id_entrep' => $id_entrep, 'id_city' => $id_city];

        $dataTables = 'v1';
        return view('usb.incomeentrep',
            //compact('enterprise','city','donatetype','donateworth')
            compact('usbincome', 'projects', 'currency', 'title_two', 'param_url', 'dataTables')
        )
            ->with(
                [
                    'pageTitle' => "سجل مدخولات {$a_title}",
                    'subTitle' => 'سجل المدخولات للحمعية',
                ]
            );
    }

    public function showReport(Request $request, $id_entrep=null)
    {

        if ($request->fromDate != null and $request->toDate != null) {
            $request->session()->put('showLineFromDate', $request->fromDate);
            $request->session()->put('showLineToDate', $request->toDate);
        }

        if (!$request->session()->has('showLineFromDate')) {
            $request->session()->put('showLineFromDate', date('Y-01-01'));
        }

        if (!$request->session()->has('showLineToDate')) {
            $request->session()->put('showLineToDate', date('Y-12-31'));
        }

        $chekZka = substr($request->user()->username,-3) =='zka' ? 1:0 ;

        $lastDayDate = (new \DateTime())->modify('-1 day')->format('Y-m-d');

        if($chekZka &&  $request->session()->get('showLineFromDate') <$lastDayDate){
            //משתממש ZKA לא יכול לחזור בתאריכים יותר מיום אחד
            $request->session()->put('showLineFromDate', date('Y-m-d'));
        }


        $showLineFromDate = $request->session()->get('showLineFromDate');
        $showLineToDate = $request->session()->get('showLineToDate');

        $a_title="";
        if($id_entrep!=null){
            $a_title = Enterprise::find($id_entrep)->name ;
        }

        $enterprise_arr =  Enterprise::get();

        $allCity = Usbincome::select('Usbincome.id_city', 'city.city_name')
            ->distinct()
            ->leftJoin('city', 'city.city_id', '=', 'Usbincome.id_city')
            ->where('dateincome', '>=', $showLineFromDate)
            ->where('dateincome', '<=', $showLineToDate)
            ->where('id_enter', $id_entrep)
            ->get();
        //return $allCity;

        $income_title_curr = [];
        foreach ($allCity as $item_city){
            $result = Usbincome::select
            ('title_two.ttwo_text', 'currency.symbol'
                , DB::raw("round(sum(Usbincome.amount),2) as amount")
            )
                ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'Usbincome.id_titletwo')
                ->leftJoin('currency', 'currency.curn_id', '=', 'Usbincome.id_curn')
                ->where('dateincome', '>=', $showLineFromDate)
                ->where('dateincome', '<=', $showLineToDate)
                ->where('id_enter', $id_entrep)
                ->where('id_city', $item_city['id_city'])
                ->groupBy('title_two.ttwo_text', 'currency.symbol')
                ->get();
            $income_title_curr[$item_city['id_city']] = $result;
        }


        $income_typeincom_curr = [];
        foreach ($allCity as $item_city){
            $result = Usbincome::select
            ('income.name', 'currency.symbol'
                , DB::raw("round(sum(Usbincome.amount),2) as amount")
            )
                ->leftJoin('income', 'income.id', '=', 'Usbincome.id_incom')
                ->leftJoin('currency', 'currency.curn_id', '=', 'Usbincome.id_curn')
                ->where('dateincome', '>=', $showLineFromDate)
                ->where('dateincome', '<=', $showLineToDate)
                ->where('id_enter', $id_entrep)
                ->where('id_city', $item_city['id_city'])
                ->groupBy('income.name', 'currency.symbol')
                ->get();
            $income_typeincom_curr[$item_city['id_city']] = $result;
        }

        $income_proj_typeincom_curr = [];
        foreach ($allCity as $item_city){
            $result = Usbincome::select
            ('Projects.name as projectname' ,'income.name', 'currency.symbol'
                , DB::raw("round(sum(Usbincome.amount),2) as amount")
            )
                ->leftJoin('Projects', 'Projects.id', '=', 'Usbincome.id_proj')
                ->leftJoin('income', 'income.id', '=', 'Usbincome.id_incom')
                ->leftJoin('currency', 'currency.curn_id', '=', 'Usbincome.id_curn')
                ->where('dateincome', '>=', $showLineFromDate)
                ->where('dateincome', '<=', $showLineToDate)
                ->where('id_enter', $id_entrep)
                ->where('id_city', $item_city['id_city'])
                ->groupBy('Projects.name','income.name', 'currency.symbol')
                ->get();
            $income_proj_typeincom_curr[$item_city['id_city']] = $result;
        }

        $income_title_typeincom_curr = [];
        foreach ($allCity as $item_city){
            $result = Usbincome::select
            ('title_two.ttwo_text','income.name', 'currency.symbol'
                , DB::raw("round(sum(Usbincome.amount),2) as amount")
            )
                ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'Usbincome.id_titletwo')
                ->leftJoin('income', 'income.id', '=', 'Usbincome.id_incom')
                ->leftJoin('currency', 'currency.curn_id', '=', 'Usbincome.id_curn')
                ->where('dateincome', '>=', $showLineFromDate)
                ->where('dateincome', '<=', $showLineToDate)
                ->where('id_enter', $id_entrep)
                ->where('id_city', $item_city['id_city'])
                ->groupBy('title_two.ttwo_text','income.name', 'currency.symbol')
                ->get();
            $income_title_typeincom_curr[$item_city['id_city']] = $result;
        }


        $expense_title = [];
        foreach ($allCity as $item_city){
            $result =Usbexpense::select
            ('title_two.ttwo_text'
                ,DB::raw("round(sum(Usbexpense.amount),2) as amount")
            )

                ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'Usbexpense.id_titletwo')
                ->where('dateexpense', '>=', $showLineFromDate)
                ->where('dateexpense', '<=', $showLineToDate)
                ->where('id_enter',$id_entrep)
                ->where('id_city',$item_city['id_city'])
                ->groupBy('title_two.ttwo_text')
                ->get();
            $expense_title[$item_city['id_city']] = $result;
        }

        $expense_proj_title = [];
        foreach ($allCity as $item_city){
            $result =Usbexpense::select
            ('Projects.name as projectname' ,'title_two.ttwo_text'
                ,DB::raw("round(sum(Usbexpense.amount),2) as amount")
            )
                ->leftJoin('Projects', 'Projects.id', '=', 'Usbexpense.id_proj')
                ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'Usbexpense.id_titletwo')
                ->where('dateexpense', '>=', $showLineFromDate)
                ->where('dateexpense', '<=', $showLineToDate)
                ->where('id_enter',$id_entrep)
                ->where('id_city',$item_city['id_city'])
                ->groupBy('Projects.name','title_two.ttwo_text')
                ->get();
            $expense_proj_title[$item_city['id_city']] = $result;
        }

        $income_all_table = [];
        $expense_all_table = [];
        foreach ($allCity as $item_city){
            //טבלת כל ההכנסות
            $result = Usbincome::with(['enterprise', 'projects', 'city', 'income', 'currency', 'titletwo'])
                ->where('dateincome', '>=', $showLineFromDate)
                ->where('dateincome', '<=', $showLineToDate)
                ->where('id_enter', $id_entrep)
                ->where('id_city', $item_city['id_city'])
                ->get();
            $income_all_table[$item_city['id_city']] = $result;

            //טבלת כל ההוצאות
            $result = Usbexpense::with(['enterprise','projects','city','expense','titletwo'])

                ->where('dateexpense', '>=', $showLineFromDate)
                ->where('dateexpense', '<=', $showLineToDate)
                ->where('id_enter',$id_entrep)
                ->where('id_city',$item_city['id_city'])
                ->get();
            $expense_all_table[$item_city['id_city']] = $result;
        }



        //return $income_title_curr;
        return view('usb.income_expense_Report', compact('id_entrep','enterprise_arr','allCity'
            ,'income_title_curr','income_typeincom_curr'
            ,'income_proj_typeincom_curr','income_title_typeincom_curr'
            ,'expense_title','expense_proj_title','income_all_table','expense_all_table')
        )->with(
            [
                'pageTitle' => "ملخص مدخولات/مصروفات {$a_title}",
                'subTitle' => 'ملخص المدخولات/مصروفات',
            ]
        );


    }


    /**
     * @param Request $request
     * @param $id_entrep
     * @param $id_proj
     * @param $id_city
     * @return array
     * מחיזר קבלות עם אותו מספר שנרשמו לאותה עמודה אותו עיר
     */
    public function showKabala(Request $request, $id_entrep, $id_proj, $id_city){

        $rowCheck = Usbincome::with(['enterprise', 'projects', 'city', 'income', 'currency', 'titletwo'])
            ->where('id_enter',$id_entrep )
            ->where('id_proj',$id_proj )
            ->where('id_city',$id_city)
            ->where('kabala',$request->kabala )
            //->where('uuid_usb',"!=",$request->id_line)
            ->get();
        if ($rowCheck->isNotEmpty()) {
            $resultArr['status'] = true;
            $resultArr['row'] = $rowCheck;
            return $resultArr;
        }
        $resultArr['status'] = false;
        return $resultArr;
    }

    /**
     * @param $arrDate
     * @return array
     */
    private function checkBeforeSave($arrDate){
        $resultArr = [];
        if(!isset($arrDate['uuid_usb'])){
            $arrDate['uuid_usb'] ='0';
        }
        if($arrDate['id_enter']=='1' and $arrDate['id_proj']=='2'){
            //عطاء المريض - جمعية بحد ذاتها
            $rowCheckOther_CityProj = Usbincome::where('id_enter',$arrDate['id_enter'] )
                ->where('id_proj',$arrDate['id_proj'] )
                ->where('kabala',$arrDate['kabala'] )
                ->where('id_city',"!=",$arrDate['id_city'])
                ->where('uuid_usb',"!=",$arrDate['uuid_usb'])
                ->get();

            if ($rowCheckOther_CityProj->isNotEmpty()) {
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'فد تم استخدام رقم الوصل لبلد اخر';
                return $resultArr;
            }


            //בדיקת אם קיים מלפני לפי: עמותה + פרויקט +  מס קבלה + סוג תרומה + סוג מטביע
            $rowCheckExists = Usbincome::
            where('id_enter',$arrDate['id_enter'] )
                ->where('id_proj',$arrDate['id_proj'] )
                ->where('kabala',$arrDate['kabala'] )
                ->where('id_incom',$arrDate['id_incom'] )
                ->where('id_curn',$arrDate['id_curn'] )
                ->where('uuid_usb',"!=",$arrDate['uuid_usb'])
                ->get();

            if ($rowCheckExists->isNotEmpty()) {
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'فد تم ادخال مثل هذه المعلومات من قبل' ;
                return $resultArr;
            }


        }else{

            $rowCheckOther_CityProj = Usbincome::where('id_enter',$arrDate['id_enter'] )
                ->where('kabala',$arrDate['kabala'] )
                ->where(function ($query) use ($arrDate) {
                    $query->where('id_proj',"!=",$arrDate['id_proj'])
                    ->orwhere('id_city',"!=",$arrDate['id_city']);
                })
                ->where('uuid_usb',"!=",$arrDate['uuid_usb'])
                ->get();

            if ($rowCheckOther_CityProj->isNotEmpty()) {
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'فد تم استخدام رقم الوصل لمشروع اخر او بلد اخر';
                return $resultArr;
            }


            //בדיקת אם קיים מלפני לפי: עמותה + פרויקט +  מס קבלה + סוג תרומה + סוג מטביע
            $rowCheckExists = Usbincome::
            where('id_enter',$arrDate['id_enter'] )
                ->where('id_proj',$arrDate['id_proj'] )
                ->where('kabala',$arrDate['kabala'] )
                ->where('id_incom',$arrDate['id_incom'] )
                ->where('id_curn',$arrDate['id_curn'] )
                ->where('uuid_usb',"!=",$arrDate['uuid_usb'])
                ->get();

            if ($rowCheckExists->isNotEmpty()) {
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'فد تم ادخال مثل هذه المعلومات من قبل' ;
                return $resultArr;
            }

        }

        $resultArr['status'] = true;
        return $resultArr;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * $showtrep -להציג שם עמותה בעמודה הראשונה
     */
    public function storeAjax(UsbIncomeRequest $request, $id_entrep, $id_proj, $id_city)
    {
        try {
            \DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

            if ($request->id_line != 0) {
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'תקלה בשמירה';
                return response()->json($resultArr);
            }

            if (strlen($request->phone) != 0) {
                if (!is_numeric($request->phone) or strlen($request->phone) != 10) {
                    $resultArr['status'] = false;
                    $resultArr['cls'] = 'error';
                    $resultArr['msg'] = 'خطا برقم الهاتف';
                    return $resultArr;
                }
            }

            $son = null;
            if (isset($request->son)) {
                $son = '1';
            }
            $dateincome = date('Y-m-d');
            $arrDate = [
                'dateincome' => $dateincome,
                'id_enter' => $id_entrep,
                'id_proj' => $id_proj,
                'id_city' => $id_city,
                'id_incom' => $request->id_incom,
                'amount' => $request->amount,
                'id_curn' => $request->id_curn,
                'id_titletwo' => $request->id_titletwo,
                'nameclient' => $request->nameclient,
                'kabala' => $request->kabala,
                'kabladat' => $request->kabladat,
                'phone' => $request->phone,
                'son' => $son,
                'nameovid' => $request->nameovid,
                'note' => $request->note,
            ];

            $resultCheck = $this->checkBeforeSave($arrDate);
            if(!$resultCheck['status']){
                return $resultCheck;
            }

            $rowinsert = Usbincome::create($arrDate);

            $rowUsbincome = Usbincome::with(['enterprise', 'projects', 'city', 'income', 'currency', 'titletwo'])->find($rowinsert->uuid_usb);

            $rowHtml = view('layout.includes.usbincomeentrep', ['rowData' => $rowUsbincome])->render();
            //$rowHtml =view('layout.includes.usbincome',['rowData' => $rowUsbincome])->render();

            $rowHtml = trim(preg_replace("/\s+/", ' ', $rowHtml));

            $resultArr['status'] = true;
            $resultArr['cls'] = 'success';
            $resultArr['msg'] = 'تم الحفظ بنجاح';
            $resultArr['row'] = $rowUsbincome;

            $resultArr['rowHtml'] = $rowHtml;

            \DB::commit();

            return $resultArr;

        } catch (\Exception $exp) {
            \DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"

            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'حصل خطا اثناء الحفظ';
            $resultArr['errormsg'] = $exp->getMessage();
            return $resultArr;

        }


    }

    public function updateAjax(UsbIncomeRequest $request, $id_entrep, $id_proj, $id_city, $uuid_usbincome)
    {

        try {
            \DB::beginTransaction();

            if ($uuid_usbincome == 0) {
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'תקלה בשמירה';
                return response()->json($resultArr);
            }

            $rowUsbincome = Usbincome::where('id_enter', $id_entrep)
                //->where('id_proj',$id_proj)
                ->where('id_city', $id_city)
                ->find($uuid_usbincome);

            if (!$rowUsbincome) {
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'שורה לא קיימת';
                return response()->json($resultArr);
            }

            if (strlen($request->phone) != 0) {
                if (!is_numeric($request->phone) or strlen($request->phone) != 10) {
                    $resultArr['status'] = false;
                    $resultArr['cls'] = 'error';
                    $resultArr['msg'] = 'خطا برقم الهاتف';
                    return $resultArr;
                }
            }

            $rowUsbincome->id_incom = $request->id_incom;
            $rowUsbincome->id_proj = $id_proj;
            $rowUsbincome->amount = $request->amount;
            $rowUsbincome->id_curn = $request->id_curn;
            $rowUsbincome->id_titletwo = $request->id_titletwo;
            $rowUsbincome->nameclient = $request->nameclient;
            $rowUsbincome->kabala = $request->kabala;
            $rowUsbincome->kabladat = $request->kabladat;
            $rowUsbincome->phone = $request->phone;
            $rowUsbincome->nameovid = $request->nameovid;
            $rowUsbincome->note = $request->note;

            $son = null;
            if (isset($request->son)) {
                $son = '1';
            }
            $rowUsbincome->son = $son;

            $resultCheck = $this->checkBeforeSave($rowUsbincome);
            if(!$resultCheck['status']){
                return $resultCheck;
            }

            $rowUsbincome->save();

            $rowUsbincome = Usbincome::with(['enterprise', 'projects', 'city', 'income', 'currency', 'titletwo'])->find($uuid_usbincome);

            $rowHtml = view('layout.includes.usbincomeentrep', ['rowData' => $rowUsbincome])->render();
            //$rowHtml =view('layout.includes.usbincome',['rowData' => $rowUsbincome])->render();

            $resultArr['status'] = true;
            $resultArr['cls'] = 'success';
            $resultArr['msg'] = 'تم الحفظ بنجاح';
            $resultArr['row'] = $rowUsbincome;
            $resultArr['rowHtml'] = $rowHtml;
            $resultArr['rowHtmlArr'] = $this->rowHtmlToArray($rowHtml);

            \DB::commit();

            return $resultArr;
        } catch (\Exception $exp) {
            \DB::rollBack();

            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'حصل خطا اثناء الحفظ';
            $resultArr['errormsg'] = $exp->getMessage();
            return $resultArr;
        }


    }

    /**
     * @param UsbIncomeRequest $request
     * @param $id_entrep
     * @param $id_proj
     * @param $id_city
     * @param $uuid_usbincome
     * @return void
     */
    public function editAjax(UsbIncomeRequest $request, $id_entrep, $id_proj, $id_city, $uuid_usbincome)
    {
        try {
            \DB::beginTransaction();
            $rowUsbincome = Usbincome::where('id_enter', $id_entrep)
                //->where('id_proj',$id_proj)
                ->where('id_city', $id_city)
                ->find($uuid_usbincome);
            if (!$rowUsbincome) {
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'שורה לא קיימת';
                return response()->json($resultArr);
            }

            $resultArr['status'] = true;
            $resultArr['cls'] = 'info';
            $resultArr['msg'] = 'עריכת שורה';
            $resultArr['row'] = $rowUsbincome;

            \DB::commit();
            return $resultArr;

        } catch (\Exception $exp) {
            \DB::rollBack();

            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'حصل خطا اثناء الحفظ';
            $resultArr['errormsg'] = $exp->getMessage();
            return $resultArr;
        }


    }

    /**
     * @param $id_entrep
     * @param $id_proj
     * @param $id_city
     * @param $uuid_usbincome
     * @return void
     */
    public function deleteAjax($id_entrep, $id_proj, $id_city, $uuid_usbincome)
    {
        try {
            \DB::beginTransaction();
            $rowUsbincome = Usbincome::where('id_enter', $id_entrep)
                //->where('id_proj',$id_proj)
                ->where('id_city', $id_city)
                ->find($uuid_usbincome);
            if (!$rowUsbincome) {
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'שורה לא קיימת';
                return response()->json($resultArr);
            }

            $rowUsbincome->delete();

            $resultArr['status'] = true;
            $resultArr['cls'] = 'success';
            $resultArr['msg'] = 'تم الحذف بنجاح';
            \DB::commit();

            return $resultArr;

        } catch (\Exception $exp) {
            \DB::rollBack();
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'حصل خطا اثناء الحفظ';
            $resultArr['errormsg'] = $exp->getMessage();
            return $resultArr;
        }


    }

}
