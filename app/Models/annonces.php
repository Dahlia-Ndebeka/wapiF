<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\etablissements;
use App\Models\utilisateurs;
use App\Models\calendriers;
use App\Models\annonce_images;

class annonces extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 
                            'description', 
                            'date', 
                            'type',  
                            'prix',
                            'image_couverture', 
                            'lieu',
                            'latitude',
                            'longitude',
                            'etat', 
                            'actif', 
                            'utilisateurs_id', 
                            'sous_categories_id', 
                            'calendriers_id'];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];


    // Acces a l'Etablissement

    public function Etablissements()
    {
        return $this->belongsToMany(etablissements::class);
    }


    public function Utilisateurs()
    { 
        return $this->belongsTo(utilisateurs::class); 
    }


    public function Calendriers()
    { 
        return $this->belongsTo(calendriers::class); 
    }
    

    public function AnnonceImage() 
    { 
        return $this->hasMany(annonce_images::class); 
    }

}