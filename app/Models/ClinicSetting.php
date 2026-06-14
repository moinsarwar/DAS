<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicSetting extends Model
{
    protected $fillable = [
        'phone',
        'address',
        'logo_path',
        'landline',
        'contact_email',
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
        'social_instagram',
        'doctors_title',
        'doctors_description'
    ];

    protected $casts = [
        'features' => 'array',
        'faqs' => 'array',
    ];
}
