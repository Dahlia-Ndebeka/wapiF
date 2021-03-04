<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\etablissements;
use App\Models\arrondissements;
use App\Models\villes;

class departements extends Model
{
    use HasFactory;

    protected $fillable = ['libelle', 'pays_id'];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function Etablissements()
    {
        return $this->hasManyThrough(etablissements::class, arrondissements::class, villes::class);
    }

}
