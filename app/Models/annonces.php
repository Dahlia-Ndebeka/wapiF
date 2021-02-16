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
                            'etat', 
                            'date', 
                            'type',                
                            'image_couverture', 
                            'lieu', 
                            'actif', 
                            'utilisateurs_id',                
                            'etablissements_id', 
                            'sous_categories_id', 
                            'calendriers_id'];

    public function Etablissements()
    { 
        return $this->belongsTo(etablissements::class); 
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
