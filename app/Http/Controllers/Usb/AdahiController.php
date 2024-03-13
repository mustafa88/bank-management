<?php

namespace App\Http\Controllers\Usb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Usb\AdahiRequest;
use App\Models\bank\City;
use App\Models\Bank\Title_two;
use App\Models\Usb\Adahi;
use App\Traits\HelpersTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdahiController extends Controller
{
    use HelpersTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function  index(Request $request,$id_city)
    {
        //return 'aa';
        if($request->fromDate!=null and $request->toDate!=null){
            $request->session()->put('showLineFromDate',$request->fromDate);
            $request->session()->put('showLineToDate',$request->toDate);
        }

        if(!$request->session()->has('showLineFromDate')){
            $request->session()->put('showLineFromDate',date('Y-01-01'));
        }

        if(!$request->session()->has('showLineToDate')){
            $request->session()->put('showLineToDate',date('Y-12-31'));
        }

        $showLineFromDate = $request->session()->get('showLineFromDate');
        $showLineToDate = $request->session()->get('showLineToDate');

        $a_title = City::find($id_city)->city_name;

        $adahi = Adahi::with('titletwo')
            ->where('datewrite', '>=', $showLineFromDate)
            ->where('datewrite', '<=', $showLineToDate)
            ->where('id_city', $id_city)
            ->get();


        $param_url = ['id_city'=>$id_city];

        $title_two = Title_two::Where('ttwo_one_id', 1)->get();
        $dataTables='v1';

        return view('usb.adahi',
            //compact('enterprise','city','donatetype','donateworth')
        //'usbincome', 'projects', 'currency',
            compact( 'adahi','title_two','param_url', 'dataTables')
        )
            ->with(
                [
                    'pageTitle' => "اضاحي عطاء -  {$a_title}",
                    'subTitle' => 'سجل مشروع الاضاحي',
                ]
            );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAjax(Request $request,$id_city)
    {
        $resultArr = array();
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

            if($request->cowseven > 6){
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'خطا بعدد الاسباع';
                return $resultArr;
            }



            $waitthll = isset($request->waitthll)?'1':null;
            $partahadi = isset($request->partahadi)?'1':null;
            $son = isset($request->son)?'1':null;

            $datewrite = date('Y-m-d');

            $arrDate = [
                'datewrite' => $datewrite,
                'id_city' => $id_city,
                'invoice' => $request->invoice,
                'invoicedate' => $request->invoicedate,
                'nameclient' => $request->nameclient,

                'sheep' => is_null($request->sheep) ? 0 : $request->sheep,
                'cowseven' => is_null($request->cowseven) ? 0 : $request->cowseven,
                'cow' => is_null($request->cow) ? 0 : $request->cow,
                'expens' => is_null($request->expens) ? 0 : $request->expens,

                'totalmoney' => $request->totalmoney,
                'id_titletwo' => $request->id_titletwo,
                'phone' => $request->phone,
                'waitthll' => $waitthll,
                'partahadi' => $partahadi,
                'partdesc' => $request->partdesc,
                'son' => $son,
                'nameovid' => $request->nameovid,
                'note' => $request->note,
            ];

            $arrDate['sheepprice']= 2000 * $arrDate['sheep'];
            $arrDate['cowsevenprice']= 1400 * $arrDate['cowseven'];
            $arrDate['cowprice']= 9800 * $arrDate['cow'];

            if(($arrDate['waitthll']=='1' or $arrDate['partahadi']=='1') and  $arrDate['phone']==null){
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'يرجى ادخال هاتف' ;
                return $resultArr;
            }


            $rowAdahi_Check = Adahi::where('invoice', $request->invoice)->get();
            if ($rowAdahi_Check->count()!=0) {
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'حصل خطا بعملية الحفظ - تم استخدام رقم الوصل' ;
                return $resultArr;
            }

            $rowinsert = Adahi::create($arrDate);

            $rowAdahi = Adahi::with('titletwo')->find($rowinsert->uuid_adha);

            $rowHtml = view('layout.includes.adahi', ['rowData' => $rowAdahi])->render();
            $rowHtml = trim(preg_replace("/\s+/", ' ', $rowHtml));

            $resultArr['status'] = true;
            $resultArr['cls'] = 'success';
            $resultArr['msg'] = 'تم الحفظ بنجاح';
            $resultArr['row'] = $rowAdahi;

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


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editAjax($id_city, $uuid_adahi)
    {
        try {
            \DB::beginTransaction();
            $rowAdahi = Adahi::where('id_city', $id_city)->find($uuid_adahi);

            if (!$rowAdahi) {
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'שורה לא קיימת' . $uuid_adahi;
                return response()->json($resultArr);
            }


            $resultArr['status'] = true;
            $resultArr['cls'] = 'info';
            $resultArr['msg'] = 'עריכת שורה';
            $resultArr['row'] = $rowAdahi;
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAjax(AdahiRequest $request, $id_city, $uuid_adahi)
    {

        try {
            \DB::beginTransaction();

            if ($uuid_adahi == 0) {
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'תקלה בשמירה';
                return response()->json($resultArr);
            }

            $rowAdahi = Adahi::where('id_city', $id_city)->find($uuid_adahi);

            if (!$rowAdahi) {
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'שורה לא קיימת' . $uuid_adahi;
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



            if($request->cowseven > 6){
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'خطا بعدد الاسباع';
                return $resultArr;
            }

                $rowAdahi->invoice= $request->invoice;
                $rowAdahi->invoicedate= $request->invoicedate;
                $rowAdahi->nameclient= $request->nameclient;

                $rowAdahi->sheep = is_null($request->sheep) ? 0 : $request->sheep;
                $rowAdahi->cowseven = is_null($request->cowseven) ? 0 : $request->cowseven;
                $rowAdahi->cow = is_null($request->cow) ? 0 : $request->cow;
                $rowAdahi->expens = is_null($request->expens) ? 0 : $request->expens;
                $rowAdahi->totalmoney= $request->totalmoney;

                $rowAdahi->id_titletwo= $request->id_titletwo;
                $rowAdahi->phone= $request->phone;
                $rowAdahi->waitthll= isset($request->waitthll)?'1':null;
                $rowAdahi->partahadi= isset($request->partahadi)?'1':null;
                $rowAdahi->partdesc= $request->partdesc;
                $rowAdahi->son= isset($request->son)?'1':null;
                $rowAdahi->nameovid= $request->nameovid;
                $rowAdahi->note= $request->note;


                $rowAdahi->sheepprice= 2000 * $rowAdahi->sheep;
                $rowAdahi->cowsevenprice= 1400 * $rowAdahi->cowseven;
                $rowAdahi->cowprice= 9800 * $rowAdahi->cow;


                if(($rowAdahi->waitthll=='1' or $rowAdahi->partahadi=='1') and  $rowAdahi->phone==null){
                    $resultArr['status'] = false;
                    $resultArr['cls'] = 'error';
                    $resultArr['msg'] = 'يرجى ادخال هاتف' ;
                    return $resultArr;
                }

                $rowAdahi_Check = Adahi::where('invoice', $request->invoice)->get();
                if ($rowAdahi_Check->count()!=1 or $rowAdahi_Check[0]['uuid_adha']!=$uuid_adahi) {
                    $resultArr['status'] = false;
                    $resultArr['cls'] = 'error';
                    $resultArr['msg'] = 'حصل خطا بعملية الحفظ - رقم وصل مستخدم' ;
                    return $resultArr;
                }

            $rowAdahi->save();

            $rowAdahi = Adahi::with('titletwo')->find($uuid_adahi);

            $rowHtml = view('layout.includes.adahi', ['rowData' => $rowAdahi])->render();
            $rowHtml = trim(preg_replace("/\s+/", ' ', $rowHtml));

            $resultArr['status'] = true;
            $resultArr['cls'] = 'success';
            $resultArr['msg'] = 'تم الحفظ بنجاح';
            $resultArr['row'] = $rowAdahi;
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteAjax($id_city, $uuid_adahi)
    {
        try {
            \DB::beginTransaction();

            $rowAdahi = Adahi::where('id_city', $id_city)->find($uuid_adahi);
            if (!$rowAdahi) {
                $resultArr['status'] = false;
                $resultArr['cls'] = 'error';
                $resultArr['msg'] = 'שורה לא קיימת';
                return response()->json($resultArr);
            }
            $rowAdahi->delete();

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

    public function showReport(Request $request)
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

        /**
        $allCity =  Adahi::select('Adahi.id_city', 'city.city_name')
        ->distinct()
        ->leftJoin('city', 'city.city_id', '=', 'Adahi.id_city')
        ->where('datewrite', '>=', $showLineFromDate)
        ->where('datewrite', '<=', $showLineToDate)
        ->get();
         *
         **/



        $totalReportArr= $methodBuy = $ReportThll=$ReportPartAdhi=[];

        //////
        $result = Adahi::select
        ('city.city_name',
            DB::raw("round(sum(Adahi.sheep),0) as countsheep"),
            DB::raw("mod(round(sum(Adahi.cowseven),0),7) as  countcowseven"),
            DB::raw("round(sum(Adahi.cow),0) + FLOOR(round(sum(Adahi.cowseven),0) / 7) as countcow"),
            DB::raw("round(sum(Adahi.sheepprice),0) as sumsheepprice"),
            DB::raw("round(sum(Adahi.cowsevenprice),0) + round(sum(Adahi.cowprice),0) as sumcow"),
            DB::raw("round(sum(Adahi.expens),0) as sumexpens"),

            DB::raw("round(sum(Adahi.sheepprice),0) +
                               round(sum(Adahi.cowsevenprice),0) +
                               round(sum(Adahi.cowprice),0) +
                               round(sum(Adahi.expens),0)
                                as sumtotalall"),

        )
            ->leftJoin('city', 'city.city_id', '=', 'Adahi.id_city')
            ->where('datewrite', '>=', $showLineFromDate)
            ->where('datewrite', '<=', $showLineToDate)
            //->where('id_city', $item_city['id_city'])
            ->groupBy('city.city_name')
            ->orderBy('id_city', 'asc')
            ->get();

        $totalReportArr= $result;

        ///////
        $result = Adahi::select
        ('city.city_name','title_two.ttwo_text',

            DB::raw("round(sum(Adahi.sheepprice),0) +
                               round(sum(Adahi.cowsevenprice),0) +
                               round(sum(Adahi.cowprice),0) +
                               round(sum(Adahi.expens),0)
                                as sumtotalall"),

        )
            ->leftJoin('city', 'city.city_id', '=', 'Adahi.id_city')
            ->leftJoin('title_two', 'title_two.ttwo_id', '=', 'Adahi.id_titletwo')
            ->where('datewrite', '>=', $showLineFromDate)
            ->where('datewrite', '<=', $showLineToDate)
            //->where('id_city', $item_city['id_city'])
            ->groupBy('city.city_name' ,'title_two.ttwo_text')
            ->orderBy('id_city', 'asc')
            ->orderBy('ttwo_text', 'asc')
            ->get();

        $methodBuy= $result;
        //return $methodBuy;
        ///////
        $result = Adahi::with('city')
            ->where('datewrite', '>=', $showLineFromDate)
            ->where('datewrite', '<=', $showLineToDate)
            //->where('id_city', $item_city['id_city'])
            ->where('waitthll','1')
            ->orderBy('id_city', 'asc')
            ->get();
        $ReportThll = $result;

        ///////
        $result = Adahi::with('city')
            //::select('nameclient','phone','partdesc')
            ->where('datewrite', '>=', $showLineFromDate)
            ->where('datewrite', '<=', $showLineToDate)
            //->where('id_city', $item_city['id_city'])
            ->where('partahadi','1')
            ->orderBy('id_city', 'asc')
            ->get();

        $ReportPartAdhi = $result;


        $ReportAllTableAdahi = Adahi::with('city','titletwo')
            ->where('datewrite', '>=', $showLineFromDate)
            ->where('datewrite', '<=', $showLineToDate)
            ->orderBy('id_city', 'asc')
            ->get();


        return view('usb.adahi_Report',
            compact('totalReportArr','methodBuy'
                ,'ReportThll','ReportPartAdhi','ReportAllTableAdahi')
        )->with(
            [
                'pageTitle' => "ملخص الاضاحي",
                'subTitle' => 'ملخص الاضاحي',
            ]
        );

    }

}
