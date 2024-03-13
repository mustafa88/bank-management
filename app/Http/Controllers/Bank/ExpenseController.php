<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\bank\ExpenseRequset;
use App\Models\Bank\Enterprise;
use App\Models\Bank\Income;
use App\Models\Bank\Projects;
use App\Models\Bank\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function show()
    {
        $enterprise = Enterprise::with(['project.expense' => function ($query) {
            $query->where('inactive', '=', 0);
        }])->get()->toArray();
        return view('managetable.expense', compact('enterprise'))
            ->with(
                [
                    'pageTitle' => "جدول انواع المصروفات",
                    'subTitle' => 'قائمة بانواع المصروفات',
                ]
            );
    }

    function showExpenseAndIncome(){
        //הצגת כל הכנסות והוצאות
        $expense= Expense::get()->toArray();
        $income = Income::get()->toArray();
        return view('managetable.expense_income', compact('expense','income'))
            ->with(
                [
                    'pageTitle' => "جدول المصروفات والمدخولات",
                    'subTitle' => 'قائمة بجميع المصروفات والمدخولات',

                ]
            );


    }

    public function showById($id)
    {
        $project = Projects::with(
            [
                'enterprise',
                'expense' => function ($query) {
                    $query->where('inactive', '=', 0);
                }
            ])->find($id)->toArray();

        return view('managetable.expense_edit', compact('project'))
            ->with(
                [
                    'pageTitle' => "تعديل قائمة المصروفات للمشاريع",
                ]
            );
    }

    public function store(ExpenseRequset $requset){

        $arrDate = [
            'name' => $requset->name,
        ];
        Expense::create($arrDate);
        return redirect()->back()->with("success", "تم الحفظ بنجاح");
    }

    public function storeOLD(ExpenseRequset $requset, $id){
        $project = Projects::find($id);
        if (!$project) {
            return abort('404');
        }

        $arrDate = [
            'id_projects' => $id,
            'name' => $requset->name,
        ];
        Expense::create($arrDate);
        return redirect()->back()->with("success", "تم الحفظ بنجاح");
    }

    /**
     * @return void
     * מחזיר כל סוגי ההכנסות ל]רויקט מסויים
     */
    public function getByProjects($id_proj){
        $expense = Expense::whereHas('projects', function($q) use ($id_proj){
            $q->where('projects.id', $id_proj)->where('inactive','0');

        })->get();

        return $expense;
    }


}
