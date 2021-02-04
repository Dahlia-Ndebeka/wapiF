<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class souscategories extends Model
{
    use HasFactory;
    
    protected $fillable = ['nomSousCategorie', 'categories_id'];
}
