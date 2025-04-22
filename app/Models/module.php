<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'module';

    protected $fillable =[

        'title',
        'contenu',
        'duree',
        'ordre',
        'date_de_creation',
        'type_contenu',
        'cours_id',
    ];

public function cours(){

   return $this->belongsTo(Cours::class);
}








}
