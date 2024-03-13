<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank\Donatetype;
use Illuminate\Http\Request;
use App\Http\Requests\Bank\DonatetypeRequest;
use Illuminate\Support\Facades\Storage;
class DonateTypeController extends Controller
{
    //

    public function donateType()
    {
        //מסך ראשי סוג תרומה

        $donatetype = Donatetype::get();
        $dataTables='v1';
        return view('managetable.donatetype' , compact('dataTables','donatetype'))
            ->with(
                [
                    'pageTitle' => "انواع التبرعات بقيمة",
                    'subTitle' => 'ادارة انواع التبرعات بقيمة',
                ]
            );
    }


    public function store(DonatetypeRequest $request){
        $arrDate = [
            'name' => $request->name,
            'price' => $request->price,
        ];
        Donatetype::create($arrDate);
        return redirect()->back()->with("success", "تم الحفظ بنجاح");
    }

    public function updatePriceAjax(DonatetypeRequest $request,$id_donatetype){

        if($id_donatetype == 0){
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'תקלה בשמירה';
            return response()->json($resultArr);
        }

        $donateType = Donatetype::find($id_donatetype);

        if(!$donateType){
            $resultArr['status'] = false;
            $resultArr['cls'] = 'error';
            $resultArr['msg'] = 'תקלה בשמירה';
            return response()->json($resultArr);
        }

        $donateType->price = $request->price;

        $donateType->save();

        $resultArr['status'] = true;
        $resultArr['cls'] = 'success';
        $resultArr['msg'] = 'تم الحفظ بنجاح';
        return response()->json($resultArr);

    }

    /**
     * יצוא קובץ תרומה בשווה
     * קובץ CSV
     */
    public function DonateTypeExport()
    {

        $donatetype = Donatetype::all()->toArray();
        //ddd($donateWorth);
        $str = "";
        foreach ($donatetype as $item1){
            $str .= implode(',',$item1).PHP_EOL;
        }

        $fileName = "donatetype-" . date('Y-m-d_H-i-s').".dat";
        Storage::disk('local')->put("public/{$fileName}", $str);


        return response()
            ->download(storage_path("app/public/{$fileName}"))
            ->deleteFileAfterSend(true);

        //return Storage::download('public/file111.txt');
        //Storage::delete('public/file111.txt');
    }

    public function DonateTypeImport(Request $request)
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
        if(substr($filename,0,10)!='donatetype'){
            return redirect()->back()->withErrors(['msg' => "يرحى اختيار الملف الصحيح"]);
        }
        $datacsv = array();
        $handle = fopen($tempPath, "r");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if(count($data)!=3){
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

            \DB::commit(); // Tell Laravel this transacion's all good and it can persist to DB
            return redirect()->back()->with("success", "تم الحفظ بنجاح - تم النعديل على {$updateCount} وتم حفظ {$insertCount} اسطر جديدة");

        }catch(\Exception $exp) {

            \DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return redirect()->back()->withErrors(['msg' => "حدث خطا اثناء الحفظ - لم يتم حفظ اي معلومه من الملف <BR> " . $exp->getMessage()]);
        }


    }
}
