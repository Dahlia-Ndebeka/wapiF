<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class utilisateurs extends Model
{
    use HasApiTokens, HasFactory;
    
    protected $fillable = ['login', 'password', 'email', 'photo', 'role', 'actif', 
                            'date_creation', 'nomAdministrateur', 'prenomAdministrateur', 'telephoneAdministrateur'];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
