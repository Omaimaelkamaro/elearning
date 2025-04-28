<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\option_reponse;


class OptionReponseController extends Controller
{
    public function store(Request $request,$questionId)
    {
       $user=auth()->user();
       if($user->role=='formateur'){

        $validated = $request->validate([
            'text' => 'required|string',
            'est_correct' => 'required|boolean',
        ]);
    }
        return Option_reponse::create([
            'text'=>$validated['text'],
            'est_correct'=>$validated['est_correct'],
            'question_id'=>$questionId,
        
        ]);
    }


    public function update(Request $request,$optionReponseId)
    {
       $user=auth()->user();
       if($user->role=='formateur'){

        $validated = $request->validate([
            'text' => 'required|string',
            'est_correct' => 'required|boolean',
        ]);
     }
    
    $option_reponse=Option_Reponse::find($optionReponseId);
    $option_reponse->update($validated);

    return response()->json([

        'message'=>'reponse modifié avec succés',
    ]);

    }
   
    public function destroy($optionReponseId)
    {
       $user=auth()->user();
       if($user->role=='formateur'){

      $option_reponse=option_reponse::find($optionReponseId);
      $option_reponse->delete();

       }
       return response()->json([

        'message'=>'reponse supprimé avec succés',
    ]);
}
}