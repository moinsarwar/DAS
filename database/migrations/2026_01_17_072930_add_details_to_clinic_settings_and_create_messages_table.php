<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new columns to clinic_settings
        Schema::table('clinic_settings', function (Blueprint $table) {
            $table->string('landline')->nullable()->after('phone');
            $table->string('contact_email')->nullable()->after('landline');
        });

        // Create contact_messages table
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinic_settings', function (Blueprint $table) {
            $table->dropColumn(['landline', 'contact_email']);
        });

        Schema::dropIfExists('contact_messages');
    }
};
