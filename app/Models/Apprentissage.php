<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apprentissage extends Model
{
    use HasFactory;
   
    protected $table = 'Apprentissage';


    protected $fillable = [
        'user_id',
        'cours_id',
        'progression',
        'etat',
        'date_debut',
        'dateFin',
       'derniere_activite'
];
    



public function modules()
{
    return $this->belongsToMany(Module::class, 'apprentissage_module')
                ->withPivot('est_complete', 'date_debut', 'date_fin')
                ->withTimestamps();
}


public function cours()
{
    return $this->belongsTo(Cours::class);
}
}