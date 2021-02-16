<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\utilisateurs;

class commentaires extends Model
{
    use HasFactory;

    protected $fillable = ['commentaire', 'utilisateurs_id', 'annonces_id'];

    protected $hidden = [
        'updated_at',
    ];

    public function Utilisateurs()
    { 
        return $this->belongsTo(utilisateurs::class); 
    }

}
