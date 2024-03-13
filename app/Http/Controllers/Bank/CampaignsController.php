<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\CampaignsRequest;
use App\Models\Bank\Banksdetail;
use App\Models\Bank\Campaigns;
use App\Models\Bank\Projects;
use Illuminate\Http\Request;

class CampaignsController extends Controller
{
    public function showTableById($id_projc){

        $projects = Projects::with('enterprise')->find($id_projc);

        $campaigns = Campaigns::
            where('inactive', '=', 0)
            ->where('id_proj', '=', $id_projc)
            ->get();
        return view('managetable.campaigns', compact('campaigns','projects'))
            ->with(
                [
                    'pageTitle' => "جدول الحملات للمشاريع",
                    'subTitle' => 'قائمة بجميع الحملات للمشاريع',
                ]
            );
    }

    public function store(CampaignsRequest $request ,$id_projc){

        $arrDate = [
            'id_proj' => $id_projc,
            'name_camp' => $request->name_camp,
            'inactive' => 0,
        ];
        Campaigns::create($arrDate);
        return redirect()->back()->with("success", "تم الحفظ بنجاح");
    }

    public function delete(CampaignsRequest $request ,$id_projc)
    {

        $banksdetail = Banksdetail::where('id_campn', '=', $request->id_campn)->get();
        //return $banksdetail;
        if ($banksdetail->isNotEmpty()) {
            //$resultArr['status'] = true;
            //$resultArr['cls'] = 'error';
            //$resultArr['msg'] = 'لقد تم استخدام الحمله -لا يمكن حذفها';
            return redirect()->back()->with("success", "لقد تم استخدام الحمله -لا يمكن حذفها");
        }

        //return 'a';
        $campaigns = Campaigns::where('id_proj','=',$id_projc)->findOrFail($request->id_campn);

        $campaigns->delete();

        return redirect()->back()->with("success", "تم الحذف بنجاح");

    }
}
