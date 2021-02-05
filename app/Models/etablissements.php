<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\souscategories;
use App\Models\utilisateurs;

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
                            'pays', 
                            'departement', 
                            'ville', 
                            'arrondissement', 
                            'latitude', 
                            'longitude', 
                            'souscategories_id', 
                            'utilisateurs_id'];


    public function sousCategories()
    { 
        return $this->belongsTo(souscategories::class); 
    }

    public function Utilisateurs()
    { 
        return $this->belongsTo(utilisateurs::class); 
    }

}
