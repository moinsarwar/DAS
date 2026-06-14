<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('show_in_navbar')->default(true);
            $table->boolean('show_in_footer')->default(false);
            $table->timestamps();
        });

        // Seed initial pages: "Oncology Services" and "Privacy Policy"
        \DB::table('pages')->insert([
            [
                'title' => 'Oncology Services',
                'slug' => 'oncology-services',
                'content' => '<h3>Dynamic Oncology Services</h3><p>At our clinic, we provide a wide range of specialized oncology treatments and consultant-based care including chemotherapy planning, cancer screenings, immunotherapy consulting, and general palliative support.</p><p>Contact our support staff or use our automated system to schedule an appointment with our consultant oncologists.</p>',
                'is_active' => true,
                'show_in_navbar' => true,
                'show_in_footer' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h3>Privacy Policy</h3><p>We take patient confidentiality and privacy extremely seriously. Your medical records, diagnoses, and personal information are stored securely and only accessible by authorized clinical personnel.</p><p>We do not share any medical record details with third parties without your explicit written consent.</p>',
                'is_active' => true,
                'show_in_navbar' => false,
                'show_in_footer' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
