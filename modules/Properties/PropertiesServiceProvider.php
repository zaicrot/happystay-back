<?php

namespace Modules\Properties;

use Illuminate\Support\ServiceProvider;

class PropertiesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');
    }

    public function register(): void
    {
        //
    }
}
