<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\etablissements;

class arrondissements extends Model
{
    use HasFactory;

    protected $fillable = ['libelle', 'villes_id'];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function Etablissements() 
    { 
        return $this->hasMany(etablissements::class); 
    }

}
