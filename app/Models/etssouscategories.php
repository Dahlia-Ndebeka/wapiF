<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class etssouscategories extends Model
{
    use HasFactory;

    protected $primaryKey = ['etablissements_id', 'souscategories_id'];

    // public $incrementing = false;
}
