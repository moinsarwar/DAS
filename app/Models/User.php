<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'mobile_number',
        'profile_photo',
        'cnic',
        'mr_number',
    ];

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDoctor()
    {
        return $this->role === 'doctor';
    }

    public function isPatient()
    {
        return $this->role === 'patient';
    }

    public function isReceptionist()
    {
        return $this->role === 'receptionist';
    }

    // Generate MR Number: MR-YYYYMM-XXXX
    public static function generateMrNumber()
    {
        $prefix = 'MR-' . date('Ym') . '-';
        $lastUser = self::where('mr_number', 'like', $prefix . '%')->orderBy('id', 'desc')->first();

        if (!$lastUser) {
            return $prefix . '0001';
        }

        $lastNumber = intval(substr($lastUser->mr_number, -4));
        return $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }

    // Patient appointments
    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    // Patient prescriptions
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
