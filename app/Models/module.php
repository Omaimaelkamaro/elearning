<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'formateur';

    protected $fillable =[

        'title',
        'content',
        'duree',
        'ordre',
        'type_content',
        'course_id',
    ];

public function cours(){

   return $this->belongsTo(Cours::class);
}
}
