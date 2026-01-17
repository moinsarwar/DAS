<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
    protected $fillable = ['appointment_id', 'bp', 'pulse', 'temperature', 'weight', 'height', 'notes'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
