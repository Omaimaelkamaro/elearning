<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\formateur;


class Cours extends Model
{
    use HasFactory,SoftDeletes;


    protected $table = 'cours';

   protected $fillable = [
        'title',
        'description',
        'date_de_creation',
        'duree',
        'prix',
        'niveau_de_difficulte',
        'gratuit',
        'deleted_at',
        'categorie_id',
        'formateur_id',
        'photo_path',
        'pblished',
        'langue',
    ];
    public function module()
    {
        return $this->hasMany(module::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function formateur()
    {
        return $this->belongsTo(Formateur::class);
    }
public function inscription(){

    return $this->hasMany(Inscription::class);
}

//pivot apprentissage
public function apprentissage()
{
    return $this->hasMany(Apprentissage::class);
}

public function user()
{
    return $this->belongsToMany(User::class, 'apprentissage')
                ->withPivot('progression', 'etat', 'date_debut', 'dateFin', 'derniere_activite')
                ->withTimestamps();
}


}
