<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    protected $table = 'question';
    use HasFactory;

   protected $fillable=[
      'text',
      'points',
      'quiz_id'
    ];
    
    public function quiz(){

         return $this->belongsTo(Quiz::class);

    }

    public function option_reponse(){

        return $this->hasMany(Option_reponse::class);


    }

    public function reponse_etudiant(){

        return $this->hasMany(Reponse_etudiant::class);
    }
}
