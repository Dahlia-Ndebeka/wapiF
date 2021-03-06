<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\etablissements;
use App\Models\annonces;
use App\Models\commentaires;
use App\Models\notes;


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
        'email_verified_at',
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

    public function Commentaires() 
    { 
        return $this->hasMany(commentaires::class); 
    }


    public function Notes() 
    { 
        return $this->hasMany(notes::class); 
    }


    // Verifier l'utilisateur connecte

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getReminderEmail()
    {
        return $this->email;
    }

}
