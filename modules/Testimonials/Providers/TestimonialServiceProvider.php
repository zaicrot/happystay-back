<?php

namespace Modules\Testimonials\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class TestimonialServiceProvider extends ServiceProvider
{
    protected $moduleName = 'Testimonials';
    protected $moduleNameLower = 'testimonials';

    public function boot(): void
    {
        $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(__DIR__ . '/../Routes/api.php');
    }

    public function register(): void
    {
        //
    }
}
