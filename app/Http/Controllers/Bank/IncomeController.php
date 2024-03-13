<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank\Income;
use App\Models\Bank\Enterprise;
use App\Models\Bank\Projects;
use App\Http\Requests\Bank\IncomeRequset;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
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

    public function store(IncomeRequset $requset){

        $arrDate = [
            'name' => $requset->name,
        ];
        Income::create($arrDate);
        return redirect()->back()->with("success", "تم الحفظ بنجاح");
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
