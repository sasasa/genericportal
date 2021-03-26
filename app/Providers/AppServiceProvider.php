<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        // ページネーションのCSSをbootstrapにする
        Paginator::useBootstrap();
        // 環境がproductionのときはhttpsに強制する
        if (\App::environment(['production'])) {
            $url->forceScheme('https');
        }
    }
}
