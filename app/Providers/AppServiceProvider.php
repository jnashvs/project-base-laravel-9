<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $arrBindings = [
            'App\Repositories\FileType\FileTypeRepositoryInterface' => 'App\Repositories\FileType\FileTypeRepository',
        ];

        foreach ($arrBindings as $interface => $module) {
            $this->app->bind($interface, $module);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
