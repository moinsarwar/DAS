<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clinic_settings', function (Blueprint $table) {
            $table->string('clinic_name')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->string('hero_badge')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('clinic_hours')->nullable();
            $table->string('clinic_days')->nullable();
            $table->text('notice_text')->nullable();
            $table->text('about_short')->nullable();
            $table->json('features')->nullable();
            $table->json('faqs')->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_instagram')->nullable();
        });

        // Set default values for the existing first setting row, if any exists.
        $setting = \DB::table('clinic_settings')->first();
        if ($setting) {
            \DB::table('clinic_settings')->where('id', $setting->id)->update([
                'clinic_name' => 'Multan Cancer Clinic',
                'hero_badge' => 'Excellence in Oncology',
                'hero_title' => 'Multan Cancer Clinic',
                'hero_subtitle' => 'Clinic',
                'hero_description' => 'Specialized consultant-based oncology services. We connect patients with leading oncologists through a streamlined appointment system.',
                'clinic_hours' => '02:00 PM - 08:00 PM',
                'clinic_days' => 'Mon - Sat',
                'notice_text' => 'Please Note: We provide scheduled consultant services only. 24-hour emergency services are NOT available.',
                'about_short' => 'Specialized oncology care providing expert consultations and compassion. Connecting patients with top oncologists in Multan.',
                'features' => json_encode([
                    ['icon' => 'bi-hospital', 'title' => 'Oncologist Consultants', 'description' => 'Specialized doctors available for detailed consultations and treatment planning.'],
                    ['icon' => 'bi-clock', 'title' => 'Scheduled Slots', 'description' => 'No waiting in long queues. Book your specific time slot with your preferred doctor.'],
                    ['icon' => 'bi-shield-check', 'title' => 'Digital Records', 'description' => 'Your medical history and prescriptions are securely stored and easily accessible.']
                ]),
                'faqs' => json_encode([
                    ['question' => 'Do you offer emergency services?', 'answer' => 'No, Multan Cancer Clinic is a consultant-based clinic. We do not have a 24-hour emergency department. Please visit a general hospital for emergencies.'],
                    ['question' => 'How do I book an appointment?', 'answer' => 'You must register or login to our portal. Once logged in, you can view available doctors and select a time slot that suits you.'],
                    ['question' => 'What are the clinic timings?', 'answer' => 'Our clinic operates from 02:00 PM to 08:00 PM, Monday through Saturday. Doctors have specific slots within these hours.']
                ]),
                'social_facebook' => 'https://facebook.com',
                'social_twitter' => 'https://twitter.com',
                'social_instagram' => 'https://instagram.com',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinic_settings', function (Blueprint $table) {
            $table->dropColumn([
                'clinic_name',
                'hero_title',
                'hero_subtitle',
                'hero_badge',
                'hero_description',
                'clinic_hours',
                'clinic_days',
                'notice_text',
                'about_short',
                'features',
                'faqs',
                'social_facebook',
                'social_twitter',
                'social_instagram'
            ]);
        });
    }
};
