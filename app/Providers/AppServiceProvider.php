<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\MiscController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        //
        view()->composer('*', function ($view) 
        {
            // $id_indicator = session('id_indicator');
            // $id_subdomain = session('id_subdomain');
            $id_domain = session('id_domain', 0);
            $builtUIArray = MiscController::buildReadUI($id_domain);
            $id_domain = session('id_domain');
            // $firstId = $builtUIArray['firstId'];
            $builtUIStrucutres = $builtUIArray['builtUI'];

            $builtUIIndicator = MiscController::buildISUI($id_domain);

            $builtUIStrucutresH = MiscController::buildReadUITwo($id_domain);
            $builtUIStrucutresH = $builtUIStrucutresH['builtUI'];

            $view->with('builtUIStrucutres', $builtUIStrucutres); 
            $view->with('builtUIStrucutresH', $builtUIStrucutresH); 
            $view->with('builtUIIndicator', $builtUIIndicator);  
           
        });  
        if (env('APP_ENV') !=='local') {
            $url->forceScheme('https');
          }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
