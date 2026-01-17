<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['patient_id', 'doctor_id', 'schedule_id', 'appointment_date', 'time_slot', 'status', 'problem', 'fee', 'refunded_amount'];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }

    public function vital()
    {
        return $this->hasOne(Vital::class);
    }
}
