<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\sous_categories;
use App\Models\etablissements;

class categories extends Model
{
    use HasFactory;

    protected $fillable = ['nomCategorie', 'image', 'titre'];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function sousCategorie() 
    { 
        return $this->hasMany(sous_categories::class); 
    }

    

}
