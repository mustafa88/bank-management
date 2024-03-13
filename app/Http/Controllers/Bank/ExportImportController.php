<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
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

        return view('manageabnk.exportimport')
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
                //$fileDb = Usbincome::withTrashed()->all()->toArray();
                $fileDb = Usbincome::withTrashed()->get()->toArray();
                //RETURN $fileDb;
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

        $str = "";
        foreach ($fileDb as $item1) {
            $str .= implode(',', $item1) . PHP_EOL;
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

        $dataDat = array();
        switch ($typeFile) {
            case "donate":
                //תרומה בשווה
                $lenArr = 13;
                $nameFun = 'import_donate';
            case "donatetype":
                //סוגי תרומה
                $lenArr = 3;
                $nameFun = 'import_donatetype';
                break;
            case "income":
                //הכנסות
                $lenArr = 19;
                $nameFun = 'import_income';
                break;
            case "expense":
                //הוצאות
                $lenArr = 16;
                $nameFun = 'import_expense';
                break;
            case "adahi":
                //הוצאות
                $lenArr = 25;
                $nameFun = 'import_adahi';
                break;
            default:
                return redirect()->back()->withErrors(['msg' => "خطا بنوع الملف"]);
        }

        $handle = fopen($tempPath, "r");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($data) != $lenArr) {
                ddd('error count line');
            }
            $dataDat[] = $data;
        }
        fclose($handle);

        return $this->$nameFun($dataDat);

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



    public function import_income($dataDat)
    {
        //הכנסות
        try {
            \DB::beginTransaction();

            $updateCount = 0;
            $insertCount = 0;
            foreach ($dataDat as $item) {

                $uuid_income = $item[0];
                $updated_at_file = substr($item[18], 0, 10) . " " . substr($item[18], 11, 8);

                $usbincome_check = Usbincome::withTrashed()->find($uuid_income);


                if ($usbincome_check) {
                    $updated_at_db = $usbincome_check['updated_at']->format('Y-m-d H:i:s');
                    //ddd($donateworth_check->updated_at->toW3cString());
                    //UPDATE
                    //שורה קיימת לבדוק את תאריך עדכון שונה - ואז צריך לעדכן את כל השורה אחרת מדלגים
                    //ddd($updated_at_db,$updated_at_file);
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
                        $usbincome_check->deleted_at = $item[16]==''?null:$item[16];
                        $usbincome_check->created_at = $item[17];
                        $usbincome_check->updated_at = $item[18];
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
                    'deleted_at' => $item[16]==''?null:$item[16],
                    'created_at' => $item[17],
                    'updated_at' => $item[18],
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


    public function import_expense($dataDat)
    {
        //הכנסות
        try {
            \DB::beginTransaction();

            $updateCount = 0;
            $insertCount = 0;
            foreach ($dataDat as $item) {

                $uuid_expense = $item[0];
                $updated_at_file = substr($item[15], 0, 10) . " " . substr($item[15], 11, 8);
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
                        $usbexpense_check->deleted_at = $item[13]==''?null:$item[13];
                        $usbexpense_check->created_at = $item[14];
                        $usbexpense_check->updated_at = $item[15];
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
                    'deleted_at' => $item[13]==''?null:$item[13],
                    'created_at' => $item[14],
                    'updated_at' => $item[15],
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
