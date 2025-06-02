<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory,SoftDeletes;
    protected $table ='categories';


    protected $fillable=[

        'title',
        'titre',
        'description',
        'descriptionEng',
    ];

    public function cours()
    {
        return $this->hasOne(cours::class);
    }


}
