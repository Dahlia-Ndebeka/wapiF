<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\etablissements;
use App\Models\annonces;


class utilisateurs extends Model
{
    use HasApiTokens, HasFactory;
    
    protected $fillable = ['login', 'password', 'email', 'photo', 'role', 'actif', 
                            'date_creation', 'nomAdministrateur', 'prenomAdministrateur', 'telephoneAdministrateur'];


    protected $hidden = [
        'password',
        'remember_token',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function Etablissements() 
    { 
        return $this->hasMany(etablissements::class); 
    }
    

    public function Annonces() 
    { 
        return $this->hasMany(annonces::class); 
    }

}
