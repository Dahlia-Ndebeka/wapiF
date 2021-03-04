<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pays extends Model
{
    use HasFactory;

    protected $fillable = ['libelle'];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

}