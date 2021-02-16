<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\utilisateurs;
use App\Models\arrondissements;
use App\Models\villes;
use App\Models\annonces;
use App\Models\notes;

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
                                'utilisateurs_id',
                                'categories_id'];


        public function sousCategories()
        { 
            return $this->belongsTo(souscategories::class); 
        }


        public function Utilisateurs()
        { 
            return $this->belongsTo(utilisateurs::class); 
        }


        public function Arrondissements()
        { 
            return $this->belongsTo(arrondissements::class); 
        }


        public function Villes()
        { 
            return $this->belongsTo(villes::class); 
        }


        public function Annonces() 
        { 
            return $this->hasMany(annonces::class); 
        }


        public function Notes() 
        { 
            return $this->hasMany(notes::class); 
        }
}
