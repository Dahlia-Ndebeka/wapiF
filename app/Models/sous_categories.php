<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\etablissements;
use App\Models\categories;

class sous_categories extends Model
{
    
    protected $fillable = ['nom_sous_categorie', 'categories_id'];

    public function Etablissements() 
    { 
        return $this->hasMany(etablissements::class); 
    }

    public function Categories()
    { 
        return $this->belongsTo(categories::class); 
    }

    
}
