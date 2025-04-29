<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\reponse_etudiant;
use App\Models\option_reponse;

class ReponseEtudiantController extends Controller
{
    public function store($questionId,$option_reponse_id)
    {
    $user=auth()->user();
    $option = Option_reponse::find($option_reponse_id);

        
        
        return Reponse_etudiant::create([
            'est_correcte'=>$option->est_correct,
            'question_id'=>$questionId,
            'option_reponse_id' =>$option_reponse_id,
           
        
        ]);
    }

}
