<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\annonces;

class annonce_images extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'annonces_id'];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function Annonces()
    { 
        return $this->belongsTo(annonces::class); 
    }

}
