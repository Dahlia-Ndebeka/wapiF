<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class arrondissements extends Model
{
    use HasFactory;

    protected $fillable = ['libelle', 'villes_id'];

}
