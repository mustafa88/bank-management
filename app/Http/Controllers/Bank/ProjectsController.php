<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\bank\ProjectsRequset;
use App\Models\Bank\Banksline;
use App\Models\Bank\Projects;
use Illuminate\Http\Request;
use App\Models\Bank\City;
use App\Models\Bank\Expense;
use App\Models\Bank\Income;
class ProjectsController extends Controller
{

    /**
     * @param $id
     * לפי מספר פרויקט
     * מחזיר: רשימת ערים - הוצאות - הכנסות
     */
    public function getCityIcomeExpenseByProject(Request $requset ,$id_city=null)
    {
        $id_line_bank = $requset->id_line_bank;
        $id_proj = $requset->id_proj;
        $result = Projects::with(
            [
                'city' => function ($query) {
                    $query->where('inactive', '=', 0);
                },
                'expense' => function ($query) {
                    $query->where('inactive', '=', 0)->orderBy('name');
                },
                'income' => function ($query) {
                    $query->where('inactive', '=', 0)->orderBy('name');
                },
                'campaigns' => function ($query) {
                    $query->where('inactive', '=', 0);
                },

            ])->find($id_proj);

        $typeselect="";
        $banksline = Banksline::find($id_line_bank);
        if($banksline['amountmandatory']!='0'){
            //סוג חובה
            $typeselect="expense";
        }else{
            //סוג זכות
            $typeselect="income";
        }
        $resultArr['status'] = true;
        $resultArr['cls'] = 'success';
        $resultArr['row'] = $result;
        $resultArr['typeselect'] = $typeselect;
        return response()->json($resultArr);

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * הצג  ערים לפי מספר פרויקט
     */
    public function showCityByProject($id)
    {

        $project = Projects::with(
            [
                'enterprise',
                'city' => function ($query) {
                    $query->where('inactive', '=', 0);
                }
            ])->find($id)->toArray();
        $city = City::get()->toArray();
        //$enterprise = Enterprise::with(['project.city'])->get()->toArray();
        //$temp = Projects::whereHas('City')->find(1);

        //return $city;
        return view('managetable.connect_projects_city_edit', compact('project', 'city'))
            ->with(
                [
                    'pageTitle' => "تحديد البلدان للمشروع",
                ]
            );
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * הצגת הכנסות לפי פרויקטים
     */
    function showExpenseByProject($id)
    {
        //עמותה + פרויקט מסויים ו רשימת ההכנסות שלו
        $project = Projects::with(
            [
                'enterprise',
                'expense' => function ($query) {
                    $query->where('inactive', '=', 0);
                }
            ])->find($id)->toArray();

        //טבלת ההכנסות
        $expense = Expense::get()->toArray();

        //return $expense;
        return view('managetable.connect_projects_expense_edit', compact('project', 'expense'))
            ->with(
                [
                    'pageTitle' => "تحديد المصروفات للمشروع",
                ]
            );
    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * הצגת הוצאות לפי פרויקטים
     */
    function showIncomeByProject($id)
    {
        //עמותה + פרויקט מסויים ו רשימת הוצאות שלו
        $project = Projects::with(
            [
                'enterprise',
                'income' => function ($query) {
                    $query->where('inactive', '=', 0);
                }
            ])->find($id)->toArray();

        //טבלת הוצאות
        $income = Income::get()->toArray();

        //return;
        return view('managetable.connect_projects_income_edit', compact('project', 'income'))
            ->with(
                [
                    'pageTitle' => "تحديد المدخولات للمشروع",
                ]
            );
    }
    /**
     * @param Request $requset
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\never
     * שמירה בטבלה מקשרת בין פרויקט ועיר
     */
    public function storeProjectCity(Request $requset, $id)
    {
        //return $id;
        //return $requset;

        $project = Projects::find($id);
        if (!$project) {
            return abort('404');
        }
        $cityproj = City::whereHas('Projects', function ($query) use ($id) {
            $query->where('Projects.id', '=', $id);
        })
            ->whereNotIn('city_id', $requset->selctcity)
            ->get();

        $cityproj->makeHidden(['city_name'])->toArray();

        foreach ($requset->selctcity as $value) {
            $project->city()->syncWithoutDetaching([$value => ['inactive' => 0]]);
        }

        foreach ($cityproj as $value) {
            //אפשר לבדוק אם לא השתמשו בעיר אם ניתן למחוק אותו
            $project->city()->syncWithoutDetaching([$value['city_id'] => ['inactive' => 1]]);
        }

        //var_dump($l); // one Dimensional
        //return $singleArrayCity;
        //$project->city()->attach($requset->selctcity);

        $project->city()->syncWithoutDetaching($requset->selctcity, ['inactive' => 0]);

        return redirect()->back()->with("success", "تم الحفظ بنجاح");

        //Projects::wtih(['city'])->get();

        /**
         * $arrDate = [
         * 'id_entrp' => $requset->id_entrp,
         * 'name' => $requset->name,
         * ];
         * Projects::create($arrDate);
         * return redirect()->back()->with("success", "تم الحفظ بنجاح");
         **/
    }


    public function storeProjectExpense(Request $requset, $id)
    {
        $project = Projects::find($id);
        if (!$project) {
            return abort('404');
        }
        $expenseproj = Expense::whereHas('Projects', function ($query) use ($id) {
            $query->where('Projects.id', '=', $id);
        })
            ->whereNotIn('id', $requset->selctexpense)
            ->get();

        $expenseproj->makeHidden(['name'])->toArray();

        foreach ($requset->selctexpense as $value) {
            $project->expense()->syncWithoutDetaching([$value => ['inactive' => 0]]);
        }

        foreach ($expenseproj as $value) {
            //אפשר לבדוק אם לא השתמשו בעיר אם ניתן למחוק אותו
            $project->expense()->syncWithoutDetaching([$value['id'] => ['inactive' => 1]]);
        }

        $project->expense()->syncWithoutDetaching($requset->selctexpense, ['inactive' => 0]);

        return redirect()->back()->with("success", "تم الحفظ بنجاح");

    }

    public function storeProjectincome(Request $requset, $id)
    {
        $project = Projects::find($id);
        if (!$project) {
            return abort('404');
        }
        $incomeproj = Income::whereHas('Projects', function ($query) use ($id) {
            $query->where('Projects.id', '=', $id);
        })
            ->whereNotIn('id', $requset->selctincome)
            ->get();

        $incomeproj->makeHidden(['name'])->toArray();



        foreach ($requset->selctincome as $value) {
            $project->income()->syncWithoutDetaching([$value => ['inactive' => 0]]);
        }

        foreach ($incomeproj as $value) {
            //אפשר לבדוק אם לא השתמשו בעיר אם ניתן למחוק אותו
            $project->income()->syncWithoutDetaching([$value['id'] => ['inactive' => 1]]);
        }

        $project->income()->syncWithoutDetaching($requset->selctincome, ['inactive' => 0]);

        return redirect()->back()->with("success", "تم الحفظ بنجاح");
    }

    /**
     * @param ProjectsRequset $requset
     * @return \Illuminate\Http\RedirectResponse
     * שמירת פרויקט חדש
     */
    public function store(ProjectsRequset $requset){
        //return $requset->tone_text;
        $arrDate = [
            'id_entrp' => $requset->id_entrp,
            'name' => $requset->name,
        ];
        Projects::create($arrDate);
        return redirect()->back()->with("success", "تم الحفظ بنجاح");
        //return "tone_text = " . $requset->tone_text ;
    }





}
