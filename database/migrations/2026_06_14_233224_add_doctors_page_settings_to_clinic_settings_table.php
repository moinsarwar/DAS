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
            $table->string('doctors_title')->nullable();
            $table->text('doctors_description')->nullable();
        });

        // Seed default values for existing first setting row, if any exists.
        $setting = \DB::table('clinic_settings')->first();
        if ($setting) {
            \DB::table('clinic_settings')->where('id', $setting->id)->update([
                'doctors_title' => 'Meet Our Specialist Oncologists',
                'doctors_description' => 'Highly qualified and experienced consultants dedicated to your care.',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinic_settings', function (Blueprint $table) {
            $table->dropColumn(['doctors_title', 'doctors_description']);
        });
    }
};
