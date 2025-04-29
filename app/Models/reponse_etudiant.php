<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reponse_etudiant extends Model
{
    use HasFactory;
    protected $table = 'reponse_etudiant';
protected $fillable=[
    'reponse',
    'est_correcte',
    'resultat_id',
    'question_id',
    'option_reponse_id',
];

public function question(){

    return $this->belongsTo(Question::class);
}

public function resultat(){

    return $this->belongsTo(Resultat::class);
}
public function optionReponse()
{
    return $this->belongsTo(Option_Reponse::class, 'option_reponse_id');
}

}
