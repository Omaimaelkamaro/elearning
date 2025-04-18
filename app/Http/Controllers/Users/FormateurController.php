<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

use App\Models\Formateur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use PhpParser\Node\Stmt\For_;

class FormateurController extends Controller
{

 public function index(){
    
    $formateurs = Formateur::all();
    return response()->json([

        "message"=>"voici la liste des formateurs",
          'formateurs'  =>    $formateurs,
        
    ]) ;


  }


    public function store(Request $request){
     
       $validated= $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:formateur',
        'dateInscription' => 'nullable|date',
        'specialite' => 'required|string|max:255',
        'verification_statut' => 'required|boolean',
    ]);

    return DB::transaction(function () use ($validated,$request){

$user=User::Create([
    'name' => $validated['name'],
    'email'=>$validated['email'],
    'password'=>Hash::make($validated['password']),
    'role' =>$validated['role'], 
    // 'dateInscription'=>$validated['dateInscription'],
    
]);


$user->formateur()->create([
    'specialite'=> $request->specialite,
    'verification_statut'=> $request->verification_statut,
]);

return response()->json([
    'message' => 'formateur créé avec succès.',
    'user' => $user,
], 201); 
 });
    
}


public function update(Request $request,Formateur $formateur){
    $user=$formateur->user;

$validated=$request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|',Rule::unique('users')->ignore($request->id),
        
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:etudiant,formateur,administrateur',
        'dateInscription' => 'nullable|date',
        'specialite' => 'required|string|max:255',
        'verification_statut' => 'required|boolean',

]);
return DB::transaction(function () use ($validated,$request,$formateur,$user) {

    $user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        // 'dateInscription' => $validated['dateInscription'],
    ]);

    $formateur->update([
        'specialite' => $validated['specialite'],
        'verification_statut' => $validated['verification_statut'],
    ]);
    

    
    //au cas de changement du role
    if ($user->wasChanged('role')) {
        $formateur->delete();
        
         switch ($validated['role']) {
            case 'etudiant':
                $user->etudiant()->create();
                break;
                
            case 'administrateur':
                $user->admin()->create(['niveau_acces'=> $request->niveau_acces]);
                break;
        }}

    // return redirect()->route('users.index')
    //     ->with('success', 'user updated successfully.');
    return response()->json([
        'message' => 'formateur modifié avec succès.',
        'formateur' => $user,
    ], 200);
    
    });
        
        }

        
public function destroy(Formateur $formateur){

 return DB::transaction(function () use ($formateur) {


    $user =$formateur->user;
  
    $user->delete();
    $formateur->delete(); 
       
    
    
    


    return response()->json([
        'message' => 'formateur supprimé avec succès.',
        'formateur' => $formateur,
    ], 200);

});
}

}










