<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ClinicSetting;
use Illuminate\Support\Facades\View;

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
        } catch (\Exception $e) {
            // Fails silently if DB connection issue or other problem, to not break app boot
        }
    }
}
