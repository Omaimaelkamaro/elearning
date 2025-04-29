<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quiz extends Model
{
    use HasFactory;
    protected $table = 'quiz';
    
    protected $fillable = [
        'titre',
        'note_minimale',
        'module_id',
        
        
    ];




    public function module(){

        return $this->belongsTo(Module::class,'module_id');

    }
    public function question(){

            return $this->hasMany(Question::class);
    
    }
    public function resultat(){

        return $this->hasMany(Resultat::class);

}

}
