<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class calendriers extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'heure_debut', 'heure_fin'];
}
