<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\question;
use Symfony\Component\Console\Question\Question as QuestionQuestion;

class QuestionController extends Controller
{
public function index($quizId){

$questions=Question::All();
return response()->json([
"message "=>"voici la liste des question de ce quiz",
"questions"=>$questions,
]);


}



 
    public function store(Request $request, $quizId)
    {

        $user=auth()->user();
        if($user->role=='formateur'){
            $validated = $request->validate([
                'text' => 'required|string',
                'points' => 'required|integer|min:1',
     
            ]);
    
        }
        
        $question = Question::create([
            'text'=>$validated['text'],
            'points'=>$validated['points'],
            'quiz_id' => $quizId,
           
        ]);

       
      
        return response()->json([
            'success' => true,
            'message' => 'Question ajoutée'
        ], 201);
    }


    public function update(Request $request, $questionId)
    {

        $user=auth()->user();
        if($user->role=='formateur'){
            $validated = $request->validate([
                'text' => 'required|string',
                'points' => 'required|integer|min:1',
     
            ]);
        }

        

        $question = Question::find($questionId);

$question->update($validated);
       
      
        return response()->json([
            'success' => true,
            'message' => 'Question modofié '
        ], 201);
    }

    public function destroy($questionId){

        $user=auth()->user();
        if($user->role=='formateur'){
           

$question=Question::find($questionId);
$question->delete();

return response()->json([
    'success' => true,
    'message' => 'Question modofié '
], 201);
}
 }



}




    
