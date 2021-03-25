<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commentaires extends Model
{
    protected $fillable = ['commentaire', 'date_commentaire', 'utilisateurs_id', 'annonces_id'];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function Utilisateurs()
    { 
        return $this->belongsTo(utilisateurs::class); 
    }
}
