<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['user_id', 'category_id', 'bio', 'qualification', 'experience_years', 'fees'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function blockedDates()
    {
        return $this->hasMany(BlockedDate::class);
    }
}
