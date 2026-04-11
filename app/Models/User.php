<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'prefix',
        'name',
        'gender',
        'employee_code',
        'designation_id',
        'centre_id',
        'landline',
        'email',
        'phone',
        'dob',
        'username',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function training_applications()
    {
        return $this->hasMany(TrainingApplication::class, 'user_id');
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin';
        }

        if ($panel->getId() === 'coordinator') {
            return $this->role === 'coordinator';
        }

        if ($panel->getId() === 'employee') {
            return $this->role === 'employee';
        }

        if ($panel->getId() === 'hq') {
            return $this->role === 'hq';
        }

        return false;
    }

    public function sendPasswordResetNotification($token)
    {
        $url = url("/reset-password?token=$token");
        Mail::to($this->email)->send(new PasswordResetMail($url));
    }
}
