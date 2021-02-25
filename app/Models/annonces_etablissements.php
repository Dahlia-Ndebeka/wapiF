<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class annonces_etablissements extends Model
{
    use HasFactory;
    protected $fillable = ['etablissements_id', 'annonces_id'];

}
