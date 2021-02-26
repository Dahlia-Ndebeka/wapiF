<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class etablissements_sous_categories extends Model
{
    use HasFactory;

    protected $fillable = ['etablissements_id', 'sous_categories_id'];
}
