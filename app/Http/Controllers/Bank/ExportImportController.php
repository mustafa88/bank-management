<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank\Currency;
use App\Models\Bank\Donatetype;
use App\Models\Bank\Donateworth;
use App\Models\Usb\Adahi;
use App\Models\Usb\Usbexpense;
use App\Models\Usb\Usbincome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExportImportController extends Controller
{
    //

    public function mainDonateExportImport()
    {

        $currency = Currency::get();
        return view('manageabnk.exportimport' , compact('currency'))
            ->with(
                [
                    'pageTitle' => "יבוא יצוא קובצים",
                    'subTitle' => 'יבוא יצוא קובצי מערכת',
                ]
            );

    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * יצאו קובץ
     */
    public function mainExport(Request $request)
    {

        if($request->typefile==0){
            return redirect()->back()->with("success", "لم يتم اختيار نوع ملف" . $request->typefile);
        }

        /**
        $flgDate=false;
        if(!empty($request->monthtype)){
            $flgDate=true;
            $yaer = substr($request->monthtype,0,4);
            $month = substr($request->monthtype,5);
        }
         *
        if($request->showexport){
            switch ($request->typefile) {
                case "donate":
                    $fileDb = Donateworth::get()->toArray();
                    $startName = "donate-";
                    break;
                case "donatetype":
                    $fileDb = Donatetype::get()->toArray();
                    $startName = "donatetype-";
                    break;
                case "income":
                    $fileDb = Usbincome::select('enterprise.name', \DB::raw('SUM(amount) as total_amount'))
                        ->with(['enterprise', 'projects', 'city', 'income', 'currency', 'titletwo'])
                        //->selectRaw('aa')
                        ->whereYear('dateincome',$yaer)->whereMonth('dateincome',$month)
                        ->groupBy('enterprise.name')
                        //->sum('amount');
                        ->get();
                    //, 'projects.name','city.name','income.name','currency.symbol','title_two.ttwo_text'
                    return $fileDb;
                    return redirect()->back()->with("success", $fileDb);
                    break;
                case "expense":
                    //$fileDb = Usbexpense::withTrashed()->all()->toArray();
                    $fileDb = Usbexpense::withTrashed()->get()->toArray();
                    $startName = "expense-";
                    break;
                case "adahi":
                    $fileDb = Adahi::withTrashed()->get()->toArray();
                    $startName = "adahi-";
                    break;
                default:
                    return redirect()->back()->withErrors(['msg' => "خطا بنوع الملف"]);
            }
        }
         **/


        if($request->btn_savecsv){

            switch ($request->typefile) {
                case "donate":
                    $fileDb = Donateworth::get()->toArray();
                    $startName = "donate-";
                    break;
                case "donatetype":
                    $fileDb = Donatetype::get()->toArray();
                    $startName = "donatetype-";
                    break;
                case "income":;
                    //مدخولات الزكاه فقط
                    $fileDb = Usbincome::whereNotNull('zaka')->withTrashed()->get()->toArray();
                    //return $fileDb;
                    $startName = "income-";
                    break;
                case "expense":
                    //$fileDb = Usbexpense::withTrashed()->all()->toArray();
                    $fileDb = Usbexpense::withTrashed()->get()->toArray();
                    $startName = "expense-";
                    break;
                case "adahi":
                    $fileDb = Adahi::withTrashed()->get()->toArray();
                    $startName = "adahi-";
                    break;
                default:
                    return redirect()->back()->withErrors(['msg' => "خطا بنوع الملف"]);
            }
        }


        $str = "{$request->typefile}@*@" . PHP_EOL;
        foreach ($fileDb as $item1) {
            $str .= implode('@*@', $item1) . PHP_EOL;
        }

        //$fileName = $startName . Str::uuid()->toString().".dat";
        $fileName = $startName . date('m-d-y-H-i') . ".dat";
        Storage::disk('local')->put("public/{$fileName}", $str);

        return response()
            ->download(storage_path("app/public/{$fileName}"))
            ->deleteFileAfterSend(true);
    }

    public function mainImport(Request $request)
    {
        $file = $request->file('filedat');
        if (!$file) {
            return redirect()->back()->withErrors(['msg' => "لم تتم قرائه الملف"]);
        }

        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize(); //Get size of uploaded file in bytes
        //ddd($filename);


        $pos = strpos($filename, '-');

        if ($pos === false) {
            return redirect()->back()->withErrors(['msg' => "خطا بقرائه الملف"]);
            ddd('error');
        }
        $typeFile = substr($filename, 0, $pos);

        switch ($typeFile) {
            case "donate":
                //תרומה בשווה
                $lenArr = 13;
            case "donatetype":
                //סוגי תרומה
                $lenArr = 3;
                break;
            case "incomeline":// הכנסות דיווח שוורת מסויימות
                //הכנסות
                $lenArr = 22;
                break;
            case "income"://הכנסות - הל ההכנסות
                //הכנסות
                $lenArr = 22;
                break;
            case "expense":
                //הוצאות
                $lenArr = 17;
                break;
            case "adahi":
                //הוצאות
                $lenArr = 25;
                break;
            default:
                return redirect()->back()->withErrors(['msg' => "خطا بنوع الملف"]);
        }



        $handle = fopen($tempPath, "r");
        $numLine=1;
        $dataDat = array();
        while (($data = fgets($handle)) !== false) {
            //while (($data = fgetcsv($handle, 1000, "@*@")) !== FALSE) {
            $data =explode("@*@", trim($data));

            if($numLine==1){
                //שורה ראשונה בקובץ שווה לשם סוג הקובץ
                if (count($data) != 2 or $data[0]!=$typeFile) {
                    ddd($numLine,$data,$typeFile,'error count line');
                }
                $numLine++;
                continue;
            }

            if (count($data) != $lenArr) {
                ddd(count($data),$lenArr, 'error count line');
            }
            $dataDat[] = $data;
        }
        fclose($handle);


        $checkSum=false;

        switch ($typeFile) {
            case "donate":
                //תרומה בשווה
                return $this->import_donate($dataDat);
            case "donatetype":
                //סוגי תרומה
                return $this->import_donatetype($dataDat);
                break;

            case "incomeline":// הכנסות דיווח שוורת מסויימות
                //הכנסות
                $currency = Currency::get();
                $listCurrencyPost  =array();
                foreach ($currency as $item){
                    $tmp="count".$item['curn_id'];
                    $listCurrencyPost[$item['curn_id']] = $request->$tmp;
                }
                return $this->import_income_line($dataDat,$listCurrencyPost);

                break;
            case "income"://הכנסות - الزكاة
                //הכנסות
                return $this->import_income_forzaka($dataDat);
                break;
            case "expense":
                //הוצאות
                return $this->import_expense($dataDat);
                break;
            case "adahi":
                //הוצאות
                return $this->import_adahi($dataDat);
                break;
            default:
                return redirect()->back()->withErrors(['msg' => "خطا بنوع الملف"]);
        }




    }





    /**
     * @param $dataDat
     * @return \Illuminate\Http\RedirectResponse
     * תרומה בשווה
     */
    public function import_donate($dataDat)
    {
        try {
            \DB::beginTransaction();

            $updateCount = 0;
            $insertCount = 0;
            foreach ($dataDat as $item) {

                $uuid_donate = $item[0];
                $updated_at_file = substr($item[12], 0, 10) . " " . substr($item[12], 11, 8);
                //ddd($updated_at);
                $donateworth_check = Donateworth::find($uuid_donate);

                if ($donateworth_check) {
                    $updated_at_db = $donateworth_check['updated_at']->format('Y-m-d H:i:s');
                    //ddd($donateworth_check->updated_at->toW3cString());
                    //UPDATE
                    //שורה קיימת לבדוק את תאריך עדכון שונה - ואז צריך לעדכן את כל השורה אחרת מדלגים
                    if ($updated_at_db != $updated_at_file) {

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
                    }
                    continue;

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

        } catch (\Exception $exp) {

            \DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return redirect()->back()->withErrors(['msg' => "حدث خطا اثناء الحفظ - لم يتم حفظ اي معلومه من الملف <BR> " . $exp->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * סוגי תרומה בשווה
     */
    public function import_donatetype($dataDat)
    {
        try {
            \DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

            $updateCount = 0;
            $insertCount = 0;
            foreach ($dataDat as $item) {

                $id_donatetype = $item[0];

                $donatetype_check = Donatetype::find($id_donatetype);

                if($donatetype_check ){
                    if($donatetype_check['name']!=$item[1] or $donatetype_check['price']!=$item[2] ){
                        $donatetype_check->name = $item[1];
                        $donatetype_check->price = $item[2];
                        $donatetype_check->save();
                        $updateCount++;
                    }

                }else{
                    //INSERT
                    Donatetype::create([
                        'id' => $item[0],
                        'name' => $item[1],
                        'price' => $item[2],
                    ]);
                    $insertCount++;
                }

            }

            \DB::commit();
            return redirect()->back()->with("success", "تم الحفظ بنجاح - تم النعديل على {$updateCount} وتم حفظ {$insertCount} اسطر جديدة");

        }catch(\Exception $exp) {

            \DB::rollBack();
            return redirect()->back()->withErrors(['msg' => "حدث خطا اثناء الحفظ - لم يتم حفظ اي معلومه من الملف <BR> " . $exp->getMessage()]);
        }


    }


    /**
     * @param $dataDat
     * @param $ArrCheckSum
     * @return \Illuminate\Http\RedirectResponse
     * שיהיה מיוחדת للئكاه
     */
    public function import_income_forzaka($dataDat )
    {

        //הכנסות
        try {
            \DB::beginTransaction();

            $currency = Currency::get();
            $listCurrency  =array();
            foreach ($currency as $item){
                $listCurrency[$item['curn_id']]=0;
            }
            $updateCount = 0;
            $insertCount = 0;
            foreach ($dataDat as $item) {

                $uuid_income = $item[0];
                $updated_at_file = substr($item[20], 0, 10) . " " . substr($item[20], 11, 8);

                $usbincome_check = Usbincome::withTrashed()->find($uuid_income);

                $listCurrency[$item[7]] += $item[6];

                if ($usbincome_check) {
                    $updated_at_db = $usbincome_check['updated_at']->format('Y-m-d H:i:s');
                    //ddd($donateworth_check->updated_at->toW3cString());
                    //UPDATE
                    //שורה קיימת לבדוק את תאריך עדכון שונה - ואז צריך לעדכן את כל השורה אחרת מדלגים
                    //ddd($updated_at_db,$updated_at_file,$item);
                    if ($updated_at_db != $updated_at_file) {
                        $usbincome_check->dateincome = $item[1];
                        $usbincome_check->id_enter = $item[2];
                        $usbincome_check->id_proj = $item[3];
                        $usbincome_check->id_city = $item[4];
                        $usbincome_check->id_incom = $item[5];
                        $usbincome_check->amount = $item[6];
                        $usbincome_check->id_curn = $item[7];
                        $usbincome_check->id_titletwo = $item[8];
                        $usbincome_check->nameclient = $item[9];
                        $usbincome_check->kabala = $item[10];
                        $usbincome_check->kabladat = $item[11];
                        $usbincome_check->phone = $item[12]==''?null:$item[12];
                        $usbincome_check->son = $item[13]==''?null:$item[13];
                        $usbincome_check->nameovid = $item[14]==''?null:$item[14];
                        $usbincome_check->note = $item[15]==''?null:$item[15];
                        $usbincome_check->zaka = $item[16]==''?null:$item[16];
                        $usbincome_check->kabala_zekou_heyov = $item[17]==''?null:$item[17];
                        $usbincome_check->export_at = $item[18]==''?null:$item[18];
                        $usbincome_check->deleted_at = $item[19]==''?null:$item[19];
                        $usbincome_check->created_at = $item[20];
                        $usbincome_check->updated_at = $item[21];
                        $usbincome_check->save();
                        $updateCount++;
                    }
                    continue;

                }
                //INSERT
                Usbincome::create([
                    'uuid_usb' => $uuid_income,
                    'dateincome' => $item[1],
                    'id_enter' => $item[2],
                    'id_proj' => $item[3],
                    'id_city' => $item[4],
                    'id_incom' => $item[5],
                    'amount' => $item[6],
                    'id_curn' => $item[7],
                    'id_titletwo' => $item[8],
                    'nameclient' => $item[9],
                    'kabala' => $item[10],
                    'kabladat' => $item[11],
                    'phone' => $item[12]==''?null:$item[12],
                    'son' => $item[13]==''?null:$item[13],
                    'nameovid' => $item[14]==''?null:$item[14],
                    'note' => $item[15]==''?null:$item[15],
                    'zaka' => $item[16]==''?null:$item[16],
                    'kabala_zekou_heyov' => $item[17]==''?null:$item[17],
                    'export_at' => $item[18]==''?null:$item[18],
                    'deleted_at' => $item[19]==''?null:$item[19],
                    'created_at' => $item[20],
                    'updated_at' => $item[21],
                ]);
                $insertCount++;
            }


            \DB::commit(); // Tell Laravel this transacion's all good and it can persist to DB
            return redirect()->back()->with("success", "تم الحفظ بنجاح - تم النعديل على {$updateCount} وتم حفظ {$insertCount} اسطر جديدة");

        } catch (\Exception $exp) {

            \DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return redirect()->back()->withErrors(['msg' => "حدث خطا اثناء الحفظ - لم يتم حفظ اي معلومه من الملف <BR> " . $exp->getMessage()]);
        }
    }


    public function import_income_line($dataDat ,$ArrCheckSum)
    {


        //הכנסות
        try {
            \DB::beginTransaction();

            $currency = Currency::get();
            $listCurrency  =array();
            $detailsCurrency = array();
            foreach ($currency as $item){
                $listCurrency[$item['curn_id']]=0;
                $detailsCurrency[$item['curn_id']]=$item['symbol'];
            }

            $countLineExists=0;
            $dataLineExists=array();
            //$sumLineExists= array();

            $countLineNew  = 0;
            $dataLineNew=array();

            $dataLineErr = array();

            //ddd($dataDat);
            foreach ($dataDat as $item) {

                $uuid_income = $item[0];
                $updated_at_file = substr($item[20], 0, 10) . " " . substr($item[20], 11, 8);

                $usbincome_check = Usbincome::withTrashed()->find($uuid_income);
                if ($usbincome_check) {

                    //اذا بالملف مبلغ لا بساوي المبلغ بالبرنامج لنفس رقم الوصلو
                    if($item[6]!=$usbincome_check['amount'] || $item[7]!=$usbincome_check['id_curn']){
                        \DB::rollBack();
                        $msg = "يتواجد بالملف تبرع برقم وصل {$item[10]} متواجد بالبرنامج ولكن بمبلغ اخر - يرجى الفحص والتدقيق<br>";
                        $msg .= "مبلغ الوصل بالملف هو {$item[6]}{$detailsCurrency[$item[7]]}<br>";
                        $msg .= "مبلغ الوصل بالبرنامج  {$usbincome_check['amount']}{$detailsCurrency[$usbincome_check['id_curn']]}<br>";
                        return redirect()->back()->withErrors(['msg' => $msg]);
                    }

                    //בקובץ שמייבים יש שורה קיימת  במסד ניתונים - לא צריך לעדכן אותה - כי קיבלנו אותו לפי
                    $countLineExists++;
                    $dataLineExists[] = "رقم الوصل {$item[10]} - مبلغ {$item[6]}{$detailsCurrency[$item[7]]}";

                    if(!isset($sumLineExists[$item[7]])){
                        $sumLineExists[$item[7]] =0;
                    }
                    //$sumLineExists[$item[7]] = isset($sumLineExists[$item[7]])? $sumLineExists[$item[7]] + $item[6] :  $item[6];



                    continue;
                }

                $listCurrency[$item[7]] += $item[6];

                //INSERT
                Usbincome::create([
                    'uuid_usb' => $uuid_income,
                    'dateincome' => $item[1],
                    'id_enter' => $item[2],
                    'id_proj' => $item[3],
                    'id_city' => $item[4],
                    'id_incom' => $item[5],
                    'amount' => $item[6],
                    'id_curn' => $item[7],
                    'id_titletwo' => $item[8],
                    'nameclient' => $item[9],
                    'kabala' => $item[10],
                    'kabladat' => $item[11],
                    'phone' => $item[12]==''?null:$item[12],
                    'son' => $item[13]==''?null:$item[13],
                    'nameovid' => $item[14]==''?null:$item[14],
                    'note' => $item[15]==''?null:$item[15],
                    'zaka' => $item[16]==''?null:$item[16],
                    'kabala_zekou_heyov' => $item[17]==''?null:$item[17],
                    'export_at' => $item[18]==''?null:$item[18],
                    'deleted_at' => $item[19]==''?null:$item[19],
                    'created_at' => $item[20],
                    'updated_at' => $item[21],
                ]);
                $countLineNew++;
                $dataLineNew[] = "رقم الوصل {$item[10]} - مبلغ {$item[6]}{$detailsCurrency[$item[7]]}";
            }

            if($countLineNew==0){
                \DB::rollBack();
                array_unshift($dataLineExists,"لم يتم الحفظ - والسبب ان الملف لا يحتوي على تبرعات جديدة");
                array_unshift($dataLineExists,"الملف يحتوي على");
                array_unshift($dataLineExists,"{$countLineExists} تبرعات موجوده بالفعل");
                array_unshift($dataLineExists,"لم يتم الحفظ - والسبب ان الملف لا يحتوي على تبرعات جديدة");
                return redirect()->back()->withErrors(['msg' => implode("<br>",$dataLineExists)]);
            }


            //ddd($listCurrency,$ArrCheckSum,array_keys($listCurrency));
            $flgCheckSum = true;
            //{$ArrCheckSum[$keyc]} - مجموع المبلغ الملف حسب العملة
            //{$itemc} - مجموع المبلغ بالملف حسب العملة
            foreach (array_keys($listCurrency) as $key){
                if($listCurrency[$key] != $ArrCheckSum[$key]){
                    $flgCheckSum = false;
                    $dataLineErr[] = "بملف التبرعات هنالك $listCurrency[$key]{$detailsCurrency[$key]} -  بينما تم استقبال $ArrCheckSum[$key]{$detailsCurrency[$key]}";
                }
            }

            if(!$flgCheckSum){
                \DB::rollBack();
                array_unshift($dataLineErr,"لم يتم الحفظ - والسبب");
                return redirect()->back()->withErrors(['msg' => implode("<br>",$dataLineErr)]);
            }

            array_unshift($dataLineNew,"تم اضافه {$countLineNew} اسطر جديدة");

            if($countLineExists!=0){
                $dataLineNew [] = "الملف يحتوي على {$countLineExists} تبرعات موجوده بالفعل بالبرنامج";
                $dataLineNew [] = "معلومات حول التبرعات الموجوده";
                $dataLineNew = array_merge($dataLineNew,$dataLineExists);
            }


            \DB::commit(); // Tell Laravel this transacion's all good and it can persist to DB
            return redirect()->back()->with(["success" => implode("<br>",$dataLineNew)]);

        } catch (\Exception $exp) {

            \DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return redirect()->back()->withErrors(['msg' => "حدث خطا اثناء الحفظ - لم يتم حفظ اي معلومه من الملف <BR> " . $exp->getMessage()]);
        }
    }


    public function import_expense($dataDat)
    {
        //הכנסות
        try {
            \DB::beginTransaction();

            $updateCount = 0;
            $insertCount = 0;
            foreach ($dataDat as $item) {

                $uuid_expense = $item[0];
                $updated_at_file = substr($item[16], 0, 10) . " " . substr($item[16], 11, 8);
                //ddd($updated_at);
                $usbexpense_check = Usbexpense::withTrashed()->find($uuid_expense);


                if ($usbexpense_check) {
                    $updated_at_db = $usbexpense_check['updated_at']->format('Y-m-d H:i:s');
                    //ddd($donateworth_check->updated_at->toW3cString());
                    //UPDATE
                    //שורה קיימת לבדוק את תאריך עדכון שונה - ואז צריך לעדכן את כל השורה אחרת מדלגים
                    if ($updated_at_db != $updated_at_file) {
                        $usbexpense_check->id_enter = $item[1];
                        $usbexpense_check->id_proj = $item[2];
                        $usbexpense_check->id_city = $item[3];
                        $usbexpense_check->dateexpense = $item[4];
                        $usbexpense_check->asmctaexpense = $item[5]==''?null:$item[5];
                        $usbexpense_check->id_expense = $item[6]==''?null:$item[6];
                        $usbexpense_check->id_expenseother = $item[7]==''?null:$item[7];
                        $usbexpense_check->amount = $item[8];
                        $usbexpense_check->id_titletwo = $item[9];
                        $usbexpense_check->dateinvoice = $item[10]==''?null:$item[10];
                        $usbexpense_check->numinvoice = $item[11]==''?null:$item[11];
                        $usbexpense_check->note = $item[12]==''?null:$item[12];
                        $usbexpense_check->feter = $item[13]==''?null:$item[13];
                        $usbexpense_check->deleted_at = $item[14]==''?null:$item[14];
                        $usbexpense_check->created_at = $item[15];
                        $usbexpense_check->updated_at = $item[16];
                        //RETURN $usbexpense_check;
                        $usbexpense_check->save();
                        $updateCount++;
                    }
                    continue;

                }
                //INSERT
                Usbexpense::create([
                    'uuid_usb' => $uuid_expense,
                    'id_enter' => $item[1],
                    'id_proj' => $item[2],
                    'id_city' => $item[3],
                    'dateexpense' => $item[4],
                    'asmctaexpense' => $item[5]==''?null:$item[5],
                    'id_expense' => $item[6]==''?null:$item[6],
                    'id_expenseother' => $item[7]==''?null:$item[7],
                    'amount' => $item[8],
                    'id_titletwo' => $item[9],
                    'dateinvoice' => $item[10]==''?null:$item[10],
                    'numinvoice' => $item[11]==''?null:$item[11],
                    'note' => $item[12]==''?null:$item[12],
                    'feter' => $item[13]==''?null:$item[13],
                    'deleted_at' => $item[14]==''?null:$item[14],
                    'created_at' => $item[15],
                    'updated_at' => $item[16],
                ]);
                $insertCount++;
            }

            \DB::commit(); // Tell Laravel this transacion's all good and it can persist to DB
            return redirect()->back()->with("success", "تم الحفظ بنجاح - تم النعديل على {$updateCount} وتم حفظ {$insertCount} اسطر جديدة");

        } catch (\Exception $exp) {

            \DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return redirect()->back()->withErrors(['msg' => "حدث خطا اثناء الحفظ - لم يتم حفظ اي معلومه من الملف <BR> " . $exp->getMessage()]);
        }
    }


    public function import_adahi($dataDat)
    {
        //הכנסות
        try {
            \DB::beginTransaction();

            $updateCount = 0;
            $insertCount = 0;
            foreach ($dataDat as $item) {

                $uuid_adahi = $item[0];
                $updated_at_file = substr($item[24], 0, 10) . " " . substr($item[24], 11, 8);
                //ddd($updated_at);
                $adahi_check = Adahi::withTrashed()->find($uuid_adahi);


                if ($adahi_check) {
                    $updated_at_db = $adahi_check['updated_at']->format('Y-m-d H:i:s');
                    //ddd($donateworth_check->updated_at->toW3cString());
                    //UPDATE
                    //שורה קיימת לבדוק את תאריך עדכון שונה - ואז צריך לעדכן את כל השורה אחרת מדלגים
                    if ($updated_at_db != $updated_at_file) {
                        $adahi_check->datewrite = $item[1];
                        $adahi_check->id_city = $item[2];
                        $adahi_check->invoice = $item[3];
                        $adahi_check->invoicedate = $item[4];
                        $adahi_check->nameclient = $item[5];
                        $adahi_check->sheepprice = $item[6];
                        $adahi_check->cowsevenprice = $item[7];
                        $adahi_check->cowprice = $item[8];
                        $adahi_check->sheep = $item[9];
                        $adahi_check->cowseven = $item[10];
                        $adahi_check->cow = $item[11];
                        $adahi_check->expens = $item[12];
                        $adahi_check->totalmoney = $item[13];
                        $adahi_check->id_titletwo = $item[14];
                        $adahi_check->phone = $item[15]==''?null:$item[15];
                        $adahi_check->waitthll = $item[16]==''?null:$item[16];
                        $adahi_check->partahadi = $item[17]==''?null:$item[17];
                        $adahi_check->partdesc = $item[18]==''?null:$item[18];
                        $adahi_check->son = $item[19]==''?null:$item[19];
                        $adahi_check->note = $item[20]==''?null:$item[20];
                        $adahi_check->nameovid = $item[21];
                        $adahi_check->deleted_at = $item[22]==''?null:$item[22];
                        $adahi_check->created_at = $item[23];
                        $adahi_check->updated_at = $item[24];

                        $adahi_check->save();
                        $updateCount++;
                    }
                    continue;

                }
                //INSERT
                Adahi::create([
                        'uuid_adha' => $uuid_adahi,
                        'datewrite' => $item[1],
                        'id_city' => $item[2],
                        'invoice' => $item[3],
                        'invoicedate' => $item[4],
                        'nameclient' => $item[5],
                        'sheepprice' => $item[6],
                        'cowsevenprice' => $item[7],
                        'cowprice' => $item[8],
                        'sheep' => $item[9],
                        'cowseven' => $item[10],
                        'cow' => $item[11],
                        'expens' => $item[12],
                        'totalmoney' => $item[13],
                        'id_titletwo' => $item[14],
                        'phone' => $item[15]==''?null:$item[15],
                        'waitthll' => $item[16]==''?null:$item[16],
                        'partahadi' => $item[17]==''?null:$item[17],
                        'partdesc' => $item[18]==''?null:$item[18],
                        'son' => $item[19]==''?null:$item[19],
                        'note' => $item[20]==''?null:$item[20],
                        'nameovid' => $item[21],
                        'deleted_at' => $item[22]==''?null:$item[22],
                        'created_at' => $item[23],
                        'updated_at' => $item[24],
                ]);
                $insertCount++;
            }

            \DB::commit(); // Tell Laravel this transacion's all good and it can persist to DB
            return redirect()->back()->with("success", "تم الحفظ بنجاح - تم النعديل على {$updateCount} وتم حفظ {$insertCount} اسطر جديدة");

        } catch (\Exception $exp) {

            \DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return redirect()->back()->withErrors(['msg' => "حدث خطا اثناء الحفظ - لم يتم حفظ اي معلومه من الملف <BR> " . $exp->getMessage()]);
        }
    }
}
