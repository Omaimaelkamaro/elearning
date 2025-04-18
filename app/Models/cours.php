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
    ];
    public function module()
    {
        return $this->hasOne(module::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function formateur()
    {
        return $this->belongsTo(Formateur::class);
    }






}
