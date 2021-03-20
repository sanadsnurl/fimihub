<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Model\EnvSetting;
use Config;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }

        $env_insta = new EnvSetting();
        foreach( $env_insta->getEnvVar() as $value) {
            // dd($value->value);
            Config::set([$value->key => $value->value]);
        }

    }
}
