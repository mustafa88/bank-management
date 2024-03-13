<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\bank\BanksRequset;
use App\Models\Bank\Banks;
use App\Models\Bank\Banksline;
use App\Models\Bank\Enterprise;
use App\Traits\BankslineTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BanksController extends Controller
{
    use BankslineTrait;
    /**
     * @param null $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * מסך ראשי הצגת רשימת בנקים
     */
    public function showTable( $id = null){
        $bank = banks::with(['enterprise','projects'])->get();
        //return $idUser =  Auth::id();

        $enterprise = Enterprise::with('project')->get();
        $bankedt = null;
        if($id){
            $bankedt = banks::with(['enterprise','projects'])->find($id);
             //return $bankedt;
        }
        //return $bank;
        return view('manageabnk.listbanks', compact('bank','enterprise','bankedt'))
            ->with(
                [
                    'pageTitle' => "جدول البنوك",
                    'subTitle' => 'قائمة بجميع البنوك',
                ]
            );
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * מסך רשי לטעינת קובת CSV לבנק
     */
    public function  mainLoadCsv()
    {
        $bank = banks::with(['enterprise','projects'])->get();

        $enterprise = Enterprise::with('project')->get();
        $bankedt = null;

        //return $bank;
        return view('manageabnk.loadcsvtobank', compact('bank','enterprise','bankedt'))
            ->with(
                [
                    'pageTitle' => "جدول البنوك",
                    'subTitle' => 'قائمة بجميع البنوك',
                ]
            );
    }

    /**
     * @param BanksRequset $requset
     * @param $id_bank
     * @return \Illuminate\Http\RedirectResponse
     * הקמת בנק חדש
     */
    public function store(BanksRequset $requset ,$id_bank){

        $id_enterproj = explode('*', $requset->id_enterproj);

        $arrDate = [
            'banknumber' => $requset->banknumber,
            'bankbranch' => $requset->bankbranch,
            'bankaccount' => $requset->bankaccount,
            'id_enter' => $id_enterproj[0],
            'id_proj' => $id_enterproj[1],
        ];
        if($id_bank==0){
            Banks::create($arrDate);
        }else{
            Banks::where('id_bank', $id_bank)
                ->update($arrDate);
        }
        return redirect()->action([BanksController::class ,'showTable']);

        //return redirect()->back()->with("success", "تم الحفظ بنجاح");
    }

    public function  storeFileCsv(Request $requset)
    {

        //https://medium.com/technology-hits/how-to-import-a-csv-excel-file-in-laravel-d50f93b98aa4

        if($requset->enterp=='0'){
            return redirect()->back()->withErrors(['msg' => "يرجى اختيار جمعية"]);
        }

        $id_bank = $requset->numberbank;
        $bank = Banks::find($id_bank);
        if(!$bank){
            return redirect()->back()->withErrors(['msg' => "يرجى اختيار بنك"]);
        }

        $file = $requset->file('filecsv');
        if (!$file) {
            return redirect()->back()->withErrors(['msg' => "لم تتم قرائه الملف"]);
        }
        $enterp =$requset->enterp;

        //ddd($requset);
        //return redirect()->back()->with("success", "تم الحفظ بنجاح  سطر جديد");
       // return redirect()->back()->withErrors(['msg' => 'The Message']);
        //return redirect()->back()->with("success", "aaaa");

        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize(); //Get size of uploaded file in bytes
        //Check for file extension and size
        $this->checkUploadedFileProperties($extension, $fileSize);
        //var_dump($filename,$extension,$tempPath,$fileSize);
        $datacsv = array();
        $handle = fopen($tempPath, "r");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $datacsv[] = $data;
        }
        fclose($handle);
        //$datacsv[1][1] =  iconv($in_charset = 'windows-1255' , $out_charset = 'UTF-8' , $datacsv[1][1]);
        //ddd($datacsv[1]);
        $counter = 0;
        $firstRow = 0;
        $dataFile = array();
        foreach ($datacsv as $item) {
            //exit;
            if ($firstRow == 0) {
                //לדלג על שורה ראשונה
                $firstRow = 1;
                continue;
            }
            $datemovement = $item[0];
            //$description = iconv($in_charset = 'windows-1255', $out_charset = 'UTF-8', $item[1]);
            $description = $item[1];
            $asmcta = $item[2];
            $amountmandatory = filter_var($item[3], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $amountright = filter_var($item[4], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if (empty($amountmandatory) or $amountmandatory == '') {
                $amountmandatory = 0;
            }
            if (empty($amountright) or $amountright == '' ) {
                $amountright = 0;
            }

            //$dateformat = \DateTime::createFromFormat('d/m/y', $datemovement);
            if(substr($datemovement,2,1)=='.'){
                if( strlen($datemovement)==10 or strlen($datemovement)==9){
                    $dateformat = \DateTime::createFromFormat('d.m.Y', $datemovement);
                }elseif (strlen($datemovement)==8){
                    $dateformat = \DateTime::createFromFormat('d.m.y', $datemovement);
                }else{
                    ddd($datemovement .'date format - error');
                }
            }elseif (substr($datemovement,2,1)=='/' ){
                if( strlen($datemovement)==10 or strlen($datemovement)==9){
                    $dateformat = \DateTime::createFromFormat('d/m/Y', $datemovement);
                }elseif (strlen($datemovement)==8){
                    $dateformat = \DateTime::createFromFormat('d/m/y', $datemovement);
                }else{
                    ddd($datemovement .' date format - error');
                }
            }elseif (substr($datemovement,4,1)=='-' ){
                if( strlen($datemovement)==10){
                    $dateformat = \DateTime::createFromFormat('Y-m-d', $datemovement);
                }elseif (strlen($datemovement)==8){
                    $dateformat = \DateTime::createFromFormat('y-m-d', $datemovement);
                }else{
                    ddd('date format - error');
                }
            }else{
                ddd('date format - error');
            }


            $datemovement = $dateformat->format('Y-m-d');

            $dataFile[$counter]['datemovement'] = $datemovement;
            $dataFile[$counter]['description'] = $description;
            $dataFile[$counter]['asmcta'] = $asmcta;
            $dataFile[$counter]['amountmandatory'] = $amountmandatory;
            $dataFile[$counter]['amountright'] = $amountright;

            $counter++;
        }

        try {
            \DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction
            foreach ($dataFile as $v_dataFile){

                $datemovement = $v_dataFile['datemovement'];
                $description = $v_dataFile['description'];
                $asmcta = $v_dataFile['asmcta'];
                $amountmandatory = $v_dataFile['amountmandatory'];
                $amountright = $v_dataFile['amountright'];


                //$duplicate = 0;
                $uploadcsv_at = date('Y-m-d');
                $arrDate = [
                    'id_bank' => $id_bank,
                    'datemovement' => $datemovement,//תארך תנועה
                    'datevalue' => $datemovement,//תאריך ערך
                    'description' => $description,
                    'asmcta' => $asmcta,
                    'amountmandatory' => $amountmandatory,
                    'amountright' => $amountright,
                    'id_enter' => $enterp,
                    'duplicate' => 0,
                    'nobank' => 0,
                    'done' => 0,
                    'uploadcsv_at' => $uploadcsv_at,
                ];
                $rowinsert = Banksline::create($arrDate);

                $this->checkIfDuplicateLine($rowinsert);
            }


            \DB::commit(); // Tell Laravel this transacion's all good and it can persist to DB

            return redirect()->back()->with("success", "تم الحفظ بنجاح {$counter} سطر جديد");
        }catch(\Exception $exp) {

            \DB::rollBack(); // Tell Laravel, "It's not you, it's me. Please don't persist to DB"
            return redirect()->back()->withErrors(['msg' => "حدث خطا اثناء الحفظ - لم يتم حفظ اي معلومه من الملف <BR> " . $exp->getMessage()]);
        }





    }
    public function checkUploadedFileProperties($extension, $fileSize)
    {
        $valid_extension = array("csv", "xlsx"); //Only want csv and excel files
        $maxFileSize = 2097152; // Uploaded file size limit is 2mb
        if (in_array(strtolower($extension), $valid_extension) and $fileSize <= $maxFileSize) {
            return true;
        } else {
            throw new \Exception('Invalid file extension', response()::HTTP_UNSUPPORTED_MEDIA_TYPE); //415 error
        }
    }
}
