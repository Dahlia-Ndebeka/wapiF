<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\utilisateurs;
use App\Models\arrondissements;
use App\Models\villes;
use App\Models\annonces;
use App\Models\notes;
use App\Models\sous_categories;
use App\Models\categories;

class etablissements extends Model
{
    use HasFactory;

    protected $fillable = ['nom_etablissement', 
                                'adresse', 
                                'telephone', 
                                'description', 
                                'heure_ouverture', 
                                'heure_fermeture', 
                                'email', 
                                'boite_postale', 
                                'site_web', 
                                'logo', 
                                'actif', 
                                'latitude', 
                                'longitude',
                                'arrondissements_id', 
                                'utilisateurs_id'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];


    public function Utilisateurs()
    { 
        return $this->belongsTo(utilisateurs::class); 
    }


    public function Arrondissements()
    { 
        return $this->belongsTo(arrondissements::class); 
    }


    public function Notes() 
    { 
        return $this->hasMany(notes::class); 
    }


    // acces au sous categorie

    public function sousCategories()
    {
        return $this->belongsToMany(sous_categories::class, 'etablissements_sous_categories');
    }


    // Acces aux annonces

    public function Annonces()
    {
        return $this->belongsToMany(annonces::class);
    }
    


}
