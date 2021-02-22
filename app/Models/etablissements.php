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
// use \Znck\Eloquent\Traits\BelongsToThrough;


class etablissements extends Model
{
    use HasFactory;
    // use HasFactory, BelongsToThrough;


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
                                'utilisateurs_id',
                                'sous_categories_id'];


    public function Utilisateurs()
    { 
        return $this->belongsTo(utilisateurs::class); 
    }


    public function Arrondissements()
    { 
        return $this->belongsTo(arrondissements::class); 
    }


    // public function Villes()
    // { 
    //     return $this->belongsTo(villes::class); 
    // }


    public function Annonces() 
    { 
        return $this->hasMany(annonces::class); 
    }


    public function Notes() 
    { 
        return $this->hasMany(notes::class); 
    }

    // acces au sous categorie

    public function sousCategories()
    { 
        return $this->belongsTo(sous_categories::class); 
    }


    // Acces a la categorie

    // public function Categories()
    // {
    //     return $this->belongsToThrough(categories::class, sous_categories::class);
    // }

    // // Acces a la ville

    // public function Villes()
    // {
    //     return $this->belongsToThrough(villes::class, arrondissements::class);
    // }



}
