<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pays extends Model
{
    use HasFactory;

    protected $fillable = ['libelle'];

}



// use App\Models\utilisateurs;

// class etablissements extends Model
// {
//     use HasFactory;

//     protected $fillable = ['nom_etablissement', 
//                             'adresse', 
//                             'telephone', 
//                             'description', 
//                             'heure_ouverture', 
//                             'heure_fermeture', 
//                             'email', 
//                             'boite_postale', 
//                             'site_web', 
//                             'logo', 
//                             'actif', 
//                             'latitude', 
//                             'longitude', 
//                             'arrondissements_id', 
//                             'utilisateurs_id',];
//                             'categories_id'];


//     public function sousCategories()
//     { 
//         return $this->belongsTo(souscategories::class); 
//     }

//     public function Utilisateurs()
//     { 
//         return $this->belongsTo(utilisateurs::class); 
//     }


// }
