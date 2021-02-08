<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class etssouscategories extends Model
{
    use HasFactory;

    protected $primaryKey = ['user_id', 'stock_id'];
    
    public $incrementing = false;
}
