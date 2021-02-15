<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\etablissements;

class villes extends Model
{
    use HasFactory;

    protected $fillable = ['libelle', 'departements_id'];
    

    public function Etablissements() 
    { 
        return $this->hasMany(etablissements::class); 
    }

}
