<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Bank\City;
use App\Models\Bank\Enterprise;
use App\Models\Bank\Projects;
use Illuminate\Http\Request;

class ProjectsCityController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * רשימת ארגונים ופעיליות שלה ובאיזה ערים פתוחה

    public function showTableWithProjectAndCity()
    {
        //https://laravel.com/docs/8.x/eloquent-relationships#eager-loading
        //Multiple Relationships
        //https://stackoverflow.com/questions/18963483/retrieving-relationships-of-relationships-using-eloquent-in-laravel/18963625

        $enterprise = Enterprise::with(['project.city' => function ($query) {
            $query->where('inactive', '=', 0);
        }])->get()->toArray();
        //return $enterprise;
        return view('managetable.connect_projects_city', compact('enterprise'))
            ->with(
                [
                    'pageTitle' => "مبنى المؤسسات حسب البلد",
                    'subTitle' => 'قائمة المشاريع للمؤسسات بالبلدان',
                ]
            );
    }
     */
    public function showTableByProject($id)
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

        //return array_column($project['city'], 'city_id');
        return view('managetable.connect_projects_city_edit', compact('project', 'city'))
            ->with(
                [
                    'pageTitle' => "تحديد البلدان المشتركة للبرنامج",
                ]
            );
    }

    public function store(Request $requset, $id)
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
}
