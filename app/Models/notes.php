<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\utilisateurs;
use App\Models\etablissements;


class notes extends Model
{
    use HasFactory;

    protected $fillable = ['commentaire', 'score', 'utilisateurs_id', 'etablissements_id'];


    public function Utilisateurs()
    { 
        return $this->belongsTo(utilisateurs::class); 
    }


    public function Etablissements()
    { 
        return $this->belongsTo(etablissements::class); 
    }

}
