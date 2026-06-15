<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ClinicSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;

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
        if (str_contains(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        try {
            // Share clinic settings with all views
            // Using a try-catch block to prevent errors during migration when table doesn't exist yet
            if (\Schema::hasTable('clinic_settings')) {
                $clinicSetting = ClinicSetting::first() ?? new ClinicSetting([
                    'phone' => '+92 300 1234567',
                    'landline' => '+92 61 1234567',
                    'contact_email' => 'info@multancancerclinic.com',
                    'address' => 'Nishtar Road, Multan',
                    'logo_path' => null
                ]);
                View::share('clinicSetting', $clinicSetting);
            }

            // Share dynamic pages globally
            if (\Schema::hasTable('pages')) {
                $navPages = \App\Models\Page::where('is_active', true)->where('show_in_navbar', true)->orderBy('title')->get();
                $footerPages = \App\Models\Page::where('is_active', true)->where('show_in_footer', true)->orderBy('title')->get();
                View::share('navPages', $navPages);
                View::share('footerPages', $footerPages);
            } else {
                View::share('navPages', collect());
                View::share('footerPages', collect());
            }
        } catch (\Exception $e) {
            View::share('navPages', collect());
            View::share('footerPages', collect());
        }
    }
}
