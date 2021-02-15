<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\etablissements;
use App\Models\utilisateurs;

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
                            'souscategories_id', 
                            'calendriers_id'];

    public function Etablissements()
    { 
        return $this->belongsTo(etablissements::class); 
    }


    public function Utilisateur()
    { 
        return $this->belongsTo(utilisateurs::class); 
    }

}
