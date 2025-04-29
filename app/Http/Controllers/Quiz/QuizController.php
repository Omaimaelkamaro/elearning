<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\quiz;
use App\Models\question;
use App\Models\Module;

class QuizController extends Controller
{
    
    public function index(){

        
    }

    public function store(Request $request, $moduleId)
{
    $user=auth()->user();
if ($user->role=='formateur'){

    
    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'note_minimale' => 'required|integer|min:0|max:100'
    ]);

}
    $quiz = Quiz::create([
        'titre' => $validated['titre'],
        'note_minimale' => $validated['note_minimale'],
        'module_id' => $moduleId,
    ]);

    return response()->json([
        "message" => "Votre quiz est ajouté avec succès",
        "quiz" => $quiz,
    ]);
}

   public function update(Request $request,$quizId){
    
    $user=auth()->user();
    if ($user->role=='formateur'){
    
        
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'note_minimale' => 'required|integer|min:0|max:100'
        ]);
    
    }

    $quiz = Quiz::find($quizId);
    $quiz->update($validated);
    return response()->json([

        "message" => "Votre quiz est modifié avec succès",
        "quiz" => $quiz,
    ]);
       
}

public function destroy($quizId){
    $user=auth()->user();
    if ($user->role=='formateur'){

 }


 $quiz = Quiz::find($quizId);
 $quiz->delete();

 return response()->json([

    "message" => "Votre quiz est supprimé avec succès",
    "quiz" => $quiz,
]);

}




}
