<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'doctor_categories';
    protected $fillable = ['name'];

    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'category_id');
    }
}
