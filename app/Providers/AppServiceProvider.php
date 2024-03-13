<?php

namespace App\Providers;

use App\Models\Program\Permissionuser;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use App\Models\bank\Banks;
use App\Models\Program\Pmenu;
use App\Models\bank\Enterprise;
use http\Exception\BadMessageException;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {



		try{
            /**
            $share_listbanks = Banks::with(['enterprise','projects'])->get();

            $share_enterprise = Enterprise::with([
                'project.city' => function ($query) {
                    $query->where('inactive', '=', 0);
                },])->get();

            $shareMenu =  Pmenu::with('submenu','program')->whereNull('id_menu_sub')->orderBy('sort', 'asc')->get();
            **/
            $shareMenu =  Pmenu::with('submenu','program','submenu.program')->whereNull('id_menu_sub')->orderBy('sort', 'asc')->get();
            //$idUser =   auth()->user();
            //$idUser =  Auth::id();
            view()->composer('*', function($view){
                $userId = auth()->id();
                //$view->with('userId',$userId);

                $shareMenuUser = Permissionuser::where('type',1)->where('id_user',$userId)->pluck('id_menu_prog')->toArray();

                $shareProgramUser = Permissionuser::where('type',2)->where('id_user',$userId)->pluck('id_menu_prog')->toArray();
                $view->with('shareMenuUser',$shareMenuUser);
                $view->with('shareProgramUser',$shareProgramUser);
            });
            $shareMenuUser = array();

        }catch(\Exception $exp) {
            $shareMenu = $shareMenuUser = array();

        }


        //View::share('share_listbank', $share_listbanks);
        //View::share('share_enterprise', $share_enterprise);
        View::share('shareMenu', $shareMenu);
        View::share('shareMenuUser', $shareMenuUser);


    }
}
