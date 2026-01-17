<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedDate extends Model
{
    protected $fillable = ['doctor_id', 'date', 'reason'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
