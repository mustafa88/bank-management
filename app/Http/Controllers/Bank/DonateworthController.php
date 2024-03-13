<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\DonateworthRequest;
use App\Models\Bank\Banksline;
use App\Models\bank\City;
use App\Models\Bank\Donatetype;
use App\Models\Bank\Donateworth;
use App\Models\Bank\Enterprise;
use App\Traits\HelpersTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DonateworthController extends Controller
{
    //
    use HelpersTrait;

    public function mainDonateOLD(Request $request)
    {
        //הצגת כל העמותות
        //$enterprise = Enterprise::with(['project'])->get()->toArray();

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

        $city = City::get();
        $donatetype = Donatetype::get();
        $enterprise = Enterprise::with(['project'])->get();

        $donateworth = Donateworth::with(['enterprise','projects','city','donatetype'])
            ->where('datedont', '>=', $showLineFromDate)
            ->where('datedont', '<=', $showLineToDate)->get();
        //return $donateworth;
        return view('manageabnk.donateOLD' , compact('enterprise','city','donatetype','donateworth'))
            ->with(
                [
                    'pageTitle' => "تبرعات بقيمة",
                    'subTitle' => 'تسجيل تبرعات بقيمة للمؤسسات',
                ]
            );
    }

    /**
     * @param Request $request
     * @param $id_entrep
     * @param $id_proj
     * @param $id_city
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * מסך ראשי לתרומה בשווה
     */
    public function mainDonate(Request $request,$id_entrep,$id_proj,$id_city)
    {
        //הצגת כל העמותות
        //$enterprise = Enterprise::with(['project'])->get()->toArray();

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

        //$city = City::get();
        $donatetype = Donatetype::get();
        //$enterprise = Enterprise::with(['project'])->get();

        $donateworth = Donateworth::with(['enterprise','projects','city','donatetype'])
            ->where('datedont', '>=', $showLineFromDate)
            ->where('datedont', '<=', $showLineToDate);
        if($id_entrep!=null){
            $donateworth->where('id_enter', $id_entrep)->where('id_proj', $id_proj)->where('id_city', $id_city);
        }
        $donateworth = $donateworth->get();
        //return $donateworth;

        $param_url = ['id_entrep'=>$id_entrep,'id_proj'=>$id_proj,'id_city'=>$id_city];
        return view('manageabnk.donate' , compact('donatetype','donateworth','param_url'))
            ->with(
                [
                    'pageTitle' => "تبرعات بقيمة",
                    'subTitle' => 'تسجيل تبرعات بقيمة للمؤسسات',
                ]
            );
    }


    /**
     * מסך ראשי ויבוא ויצוא קבץ ץרומ בשווה
     */
    public function mainDonateExportImport()
    {

        return view('manageabnk.donatecsv' )
            ->with(
                [
                    'pageTitle' => "יבוא יצוא קובץ",
                    'subTitle' => 'יבוא יצוא קובצים של תרומב',
                ]
            );

    }

    /**
     * יצוא קובץ תרומה בשווה
     * קובץ CSV
     */
    public function mainDonateExport()
    {

        $donateWorth = Donateworth::all()->toArray();
        //ddd($donateWorth);
        $str = "";
        foreach ($donateWorth as $item1){
            $str .= implode(',',$item1).PHP_EOL;
        }

        $fileName = "donate-" . Str::uuid()->toString().".dat";
        Storage::disk('local')->put("public/{$fileName}", $str);


        return response()
            ->download(storage_path("app/public/{$fileName}"))
            ->deleteFileAfterSend(true);

        //return Storage::download('public/file111.txt');
        //Storage::delete('public/file111.txt');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * טעינת קובץ
     */
    public function mainDonateImport(Request $request)
    {
        $file = $request->file('filecsv');
        if (!$file) {
            return redirect()->back()->withErrors(['msg' => "لم تتم قرائه الملف"]);
        }

        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize(); //Get size of uploaded file in bytes
        //ddd($filename);
        if(substr($filename,0,6)!='donate'){
            return redirect()->back()->withErrors(['msg' => "يرحى اختيار الملف الصحيح"]);
        }
        $datacsv = array();
        $handle = fopen($tempPath, "r");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if(count($data)!=13){
                ddd('error count line');
            }
            $datacsv[] = $data;
        }
        fclose($handle);
        //ddd($datacsv);

        try {
            \DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

            $updateCount = 0;
            $insertCount = 0;
            foreach ($datacsv as $item) {

                $uuid_donate = $item[0];
                $updated_at_file = substr($item[12],0,10) . " " . substr($item[12],11,8);
                //ddd($updated_at);
                $donateworth_check = Donateworth::find($uuid_donate);

                if($donateworth_check ){
                    $updated_at_db = $donateworth_check['updated_at']->format('Y-m-d H:i:s');
                    //ddd($donateworth_check->updated_at->toW3cString());
                    //UPDATE
                    //שורה קיימת לבדוק את תאריך עדכון שונה - ואז צריך לעדכן את כל השורה אחרת מדלגים
                    if($updated_at_db !=$updated_at_file){

                        $donateworth_check->datedont = $item[1];
                        $donateworth_check->id_enter = $item[2];
                        $donateworth_check->id_proj = $item[3];
                        $donateworth_check->id_city = $item[4];
                        $donateworth_check->id_typedont = $item[5];
                        $donateworth_check->price = $item[6];
                        $donateworth_check->quantity = $item[7];
                        $donateworth_check->amount = $item[8];
                        $donateworth_check->description = $item[9];
                        $donateworth_check->namedont = $item[10];
                        $donateworth_check->created_at = $item[11];
                        $donateworth_check->updated_at = $item[12];
                        $donateworth_check->save();
                        $updateCount++;
                        continue;
                    }else{
                        continue;
                    }

                }
                //INSERT
                Donateworth::create([
                    'uuid_donate' => $uuid_donate,
                    'datedont' => $item[1],
                    'id_enter' => $item[2],
                    'id_proj' => $item[3],
                    'id_city' => $item[4],
                    'id_typedont' => $item[5],
                    'price' => $item[6],
                    'quantity' => $item[7],
                    'amount' => $item[8],
                    'description' => $item[9],
                    'namedont' => $item[10],
                    'created_at' => $item[11],
                    'updated_at' => $item[12],
                ]);
                $insertCount++;
            }

            \DB::commit(); // Tell Laravel this transacion's all good and it can persist to DB
            return redirect()->back()->with("success", "تم الحفظ بنجاح - تم النعديل على {$updateCount} وتم حفظ {$insertCount} اسطر جديدة");

        }catch(\Exception $exp) {

            \DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return redirect()->back()->withErrors(['msg' => "حدث خطا اثناء الحفظ - لم يتم حفظ اي معلومه من الملف <BR> " . $exp->getMessage()]);
        }


    }




    public function storeAjax(DonateworthRequest $requset,$id_entrep,$id_proj,$id_city)
    {
        if($requset->id_line != 0){
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'תקלה בשמירה';
            return response()->json($resultArr);
        }


        $arrDate = [
            'datedont' => $requset->datedont,
            'id_enter' =>$id_entrep,
            'id_proj' => $id_proj,
            'id_city' => $id_city,
            'id_typedont' => $requset->id_typedont,
            'amount' => $requset->amount,
            'price' => $requset->price,
            'quantity' => $requset->quantity,
            'description' => $requset->description,
            'namedont' => $requset->namedont,
        ];

        $rowinsert = Donateworth::create($arrDate);

        $rowDonate= Donateworth::with(['enterprise','projects','city','donatetype'])->find($rowinsert->uuid_donate);


        $rowHtml = view('layout.includes.linedonate',compact('rowDonate'))->render();
        //$rowHtml = trim(preg_replace('/\s\s+/', ' ', $rowHtml));
        $rowHtml = trim(preg_replace("/\s+/", ' ', $rowHtml));

        $resultArr['status'] = true;
        $resultArr['cls'] = 'success';
        $resultArr['msg'] = 'تم الحفظ بنجاح';
        $resultArr['row'] = $rowDonate;

        $resultArr['rowHtml'] = $rowHtml;
        return response()->json($resultArr);
    }


    public function editAjax(DonateworthRequest $requset,$id_entrep,$id_proj,$id_city ,$id_donate){
        $rowDonate= Donateworth::where('id_enter', $id_entrep)
            ->where('id_proj', $id_proj)
            ->where('id_city', $id_city)
            ->find($id_donate);
        if(!$rowDonate){
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'שורה לא קיימת';
            return response()->json($resultArr);
        }
        $resultArr['status'] = true;
        $resultArr['cls'] = 'info';
        $resultArr['msg'] = 'עריכת שורה';
        $resultArr['row'] = $rowDonate;
        return response()->json($resultArr);
    }

    public function updateAjax(DonateworthRequest $requset,$id_entrep,$id_proj,$id_city ,$id_donate){

        if($id_donate == 0){
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'תקלה בשמירה';
            return response()->json($resultArr);
        }

        $rowDonate= Donateworth::where('id_enter', $id_entrep)
            ->where('id_proj', $id_proj)
            ->where('id_city', $id_city)
            ->find($id_donate);

        if(!$rowDonate){
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'שורה לא קיימת';
            return response()->json($resultArr);
        }


        $rowDonate->datedont = $requset->datedont;
        $rowDonate->id_typedont = $requset->id_typedont;
        $rowDonate->price = $requset->price;
        $rowDonate->quantity = $requset->quantity;
        $rowDonate->amount = $requset->amount;
        $rowDonate->description = $requset->description;
        $rowDonate->namedont = $requset->namedont;

        $rowDonate->save();

        $rowDonate= Donateworth::with(['enterprise','projects','city','donatetype'])->find($id_donate);

        $rowHtml = view('layout.includes.linedonate',compact('rowDonate'))->render();

        $resultArr['status'] = true;
        $resultArr['cls'] = 'success';
        $resultArr['msg'] = 'تم الحفظ بنجاح';
        $resultArr['row'] = $rowDonate;
        $resultArr['rowHtml'] = $rowHtml;
        $resultArr['rowHtmlArr'] = $this->rowHtmlToArray($rowHtml);
        return response()->json($resultArr);
    }


    public function deleteAjax($id_entrep ,$id_proj ,$id_city ,$id_donate)
    {
        $rowDonate= Donateworth::where('id_enter', $id_entrep)
            ->where('id_proj', $id_proj)
            ->where('id_city', $id_city)
            ->find($id_donate);
        if(!$rowDonate){
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'שורה לא קיימת';
            return response()->json($resultArr);
        }

        $rowDonate->delete();

        $resultArr['status'] = true;
        $resultArr['cls'] = 'success';
        $resultArr['msg'] = 'تم الحذف بنجاح';
        return response()->json($resultArr);

    }

}
