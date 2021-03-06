<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\annonces;

class calendriers extends Model
{
    use HasFactory;

    protected $fillable = ['label','date_evenement', 'heure_debut', 'heure_fin', 'annonces_id'];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function Annonces() 
    { 
        return $this->hasMany(annonces::class); 
    }

}
