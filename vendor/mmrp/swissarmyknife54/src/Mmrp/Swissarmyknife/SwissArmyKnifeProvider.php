<?php

namespace Mmrp\Swissarmyknife;

use App\Http\Middleware\Permissions;
use Illuminate\Support\ServiceProvider;
use Mmrp\Swissarmyknife\Console\ControllerMakeCommand;
use Mmrp\Swissarmyknife\Console\MigrateMakeCommand;
use Mmrp\Swissarmyknife\Console\ModelMakeCommand;

class SwissArmyKnifeProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $to_copy_folder =  __DIR__ . '/../../copy';

        $this->publishes([
            $to_copy_folder . '/User.php' => base_path('app/User.php'),

            $to_copy_folder . '/routes/web.php' => base_path('routes/web.php'),

            $to_copy_folder . '/Http/Controllers/' => base_path('app/Http/Controllers/'),
            $to_copy_folder . '/Http/middleware/' => base_path('app/Http/Middleware/'),

            $to_copy_folder . '/models/' => base_path('app/Models/'),

            $to_copy_folder . '/database/migrations' => base_path('database/migrations/'),
            $to_copy_folder . '/database/seeds' => base_path('database/seeds/'),

            $to_copy_folder . '/resources/lang' => base_path('resources/lang/'),
            $to_copy_folder . '/resources/views' => base_path('resources/views/'),

            $to_copy_folder . '/public/' => base_path('public/'),


        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ControllerMakeCommand::class,
                ModelMakeCommand::class,
                MigrateMakeCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {


    }
}
