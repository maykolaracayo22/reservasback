<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;  // Agregar este trait para usar createToken

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens;  // Asegúrate de incluir HasApiTokens

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
