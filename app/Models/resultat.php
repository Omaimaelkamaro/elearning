<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class resultat extends Model
{

    protected $table = 'resultat';
    use HasFactory;
    protected $fillable = [
        'score',
        'quiz_id',
        'user_id',
        ];


    public function reponse_etudiant(){

        return $this->hasMany(Reponse_etudiant::class);
    }
    public function user(){

        return $this->belongsTo(User::class);
    }
    
public function quiz()
{
    return $this->belongsTo(Quiz::class);
}
}

