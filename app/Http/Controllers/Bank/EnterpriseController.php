<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\EnterpriseRequset;
use App\Models\Bank\Enterprise;

class EnterpriseController extends Controller
{
    public function showTable()
    {
        //הצגת כל העמותות
        $enterprise = Enterprise::with(['project'])->get()->toArray();

        return view('managetable.enterprise', compact('enterprise'))
            ->with(
                [
                    'pageTitle' => "مبنى المؤسسات",
                    'subTitle' => 'قائمة المشاريع للمؤسسات',

                ]
            );
    }

    public function showTableStructure()
    {
        //מבנה כל העמותות - עם כל הקישורים ביניהן
        $enterprise = Enterprise::with(
            [
                'project.city' => function ($query) {
                    $query->where('inactive', '=', 0);
                },

                'project.income' => function ($query) {
                    $query->where('inactive', '=', 0);
                },
                'project.expense' => function ($query) {
                    $query->where('inactive', '=', 0);
                },

                'project.campaigns' => function ($query) {
                    $query->where('inactive', '=', 0);
                },
            ]
        )->get()
           // ->toArray()
        ;
        //return $enterprise;
        return view('reports.structure_association', compact('enterprise'))
            ->with(
                [
                    'pageTitle' => "هيكل جميع المؤسسات",
                    'subTitle' => 'هيكل الجمعية',

                ]
            );
    }


    public function store(EnterpriseRequset $requset)
    {
        $arrDate = [
            'name' => $requset->name,
        ];
        Enterprise::create($arrDate);
        return redirect()->back()->with("success", "تم الحفظ بنجاح");
    }
}
