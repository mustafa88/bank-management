<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank\Banksdetail;
use App\Models\Bank\Income;
use App\Models\Bank\Enterprise;
use App\Models\Bank\Projects;
use App\Http\Requests\Bank\IncomeRequset;
use App\Models\Usb\Usbincome;
use Illuminate\Http\Request;

class IncomeController extends Controller
{


    public function showTable()
    {
        $income = Income::get()->toArray();

        return view('managetable.income', compact('income'))
            ->with(
                [
                    'pageTitle' => "جدول انواع المدخولات",
                    'subTitle' => 'قائمة بانواع المدخولات',
                ]
            );
    }

    /**
    public function show()
    {
        $enterprise = Enterprise::with(['project.income' => function ($query) {
            $query->where('inactive', '=', 0);
        }])->get()->toArray();
        return view('managetable.income', compact('enterprise'))
            ->with(
                [
                    'pageTitle' => "جدول انواع المدخولات",
                    'subTitle' => 'قائمة بانواع المدخولات',
                ]
            );
    }
    **/

    public function showById($id)
    {
        $project = Projects::with(
            [
                'enterprise',
                'income' => function ($query) {
                    $query->where('inactive', '=', 0);
                }
            ])->find($id)->toArray();
       //$city = City::get()->toArray();
        //$enterprise = Enterprise::with(['project.city'])->get()->toArray();
        //$temp = Projects::whereHas('City')->find(1);

        //return array_column($project['city'], 'city_id');
        //return $project;
        return view('managetable.income_edit', compact('project'))
            ->with(
                [
                    'pageTitle' => "تعديل قائمة المدخولات للمشاريع",
                ]
            );
    }

    public function store(IncomeRequset $request){

        $arrDate = [
            'name' => $request->name,
        ];
        Income::create($arrDate);
        return redirect()->back()->with("success", "تم الحفظ بنجاح");
    }

    public function delete(IncomeRequset $request,$id_income){

        $countBank = Banksdetail::where('id_incom',$id_income)->count();
        $countUsb = Usbincome::where('id_incom',$id_income)->count();
        if($countBank==0 or $countUsb==0){
            //ניתן למחוק
            Income::where('id', '=', $id_income)->delete();
            return redirect()->back()->with("success", "تم الحذف بنجاح");
        }else{
            //בוצע שימוש בסוג הכנסה לא ניתן למחוק
            return redirect()->back()->with("success", "لا يمكن الحذف - لقد تم استخدام نوع التبرع");
        }

    }


    /**
     * @return void
     * מחזיר כל סוגי ההכנסות ל]רויקט מסויים
     */
    public function getByProjects($id_proj){

        $income = Income::whereHas('projects', function($q) use ($id_proj){
            $q->where('projects.id', $id_proj)->where('inactive','0');

        })->get();

        return $income;

    }
}
