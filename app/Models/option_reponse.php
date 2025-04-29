<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Question\Question;

class option_reponse extends Model
{
    protected $table = 'option_reponse';

    use HasFactory;


    protected $fillable=[
    'text',
    'est_correct',
    'question_id',
];

public function question(){

    return $this->belongsTo(Question::class);


}

}
