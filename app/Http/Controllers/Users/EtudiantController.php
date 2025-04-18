<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

use App\Models\etudiant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use PhpParser\Node\Stmt\For_;

class EtudiantController extends Controller
{

 public function index(){
    
    $etudiants = etudiant::all();
    return response()->json([

        "message"=>"voici la liste des etudiants",
          'etudiants'  =>    $etudiants,
        
    ]) ;


  }


    public function store(Request $request){
     
       $validated= $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:etudiant',
        'dateInscription' => 'nullable|date',
    ]);

    return DB::transaction(function () use ($validated,$request){

$user=User::Create([
    'name' => $validated['name'],
    'email'=>$validated['email'],
    'password'=>Hash::make($validated['password']),
    'role' =>$validated['role'], 
    // 'dateInscription'=>$validated['dateInscription'],
    
]);


$user->etudiant()->create([]);

return response()->json([
    'message' => 'etudiant créé avec succès.',
    'user' => $user,
], 201); 
 });
    
}


public function update(Request $request,etudiant $etudiant){
    $user=$etudiant->user;

$validated=$request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|',Rule::unique('users')->ignore($request->id),
        
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:etudiant,formateur,administrateur',
        'dateInscription' => 'nullable|date',
    

]);
return DB::transaction(function () use ($validated,$request,$etudiant,$user) {

    $user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        // 'dateInscription' => $validated['dateInscription'],
    ]);

    $etudiant->update([]);
    

    
    //au cas de changement du role
    if ($user->wasChanged('role')) {
        $etudiant->delete();
        
         switch ($validated['role']) {
            case 'formateur':
                $user->etudiant()->create(['specialite' => $request->specialite],
             ['verification_statut' =>$request->verification_statut]);
                break;
                
            case 'administrateur':
                $user->admin()->create(['niveau_acces'=> $request->niveau_acces]);
                break;
        }}

    // return redirect()->route('users.index')
    //     ->with('success', 'user updated successfully.');
    return response()->json([
        'message' => 'etudiant modifié avec succès.',
        'etudiant' => $user,
    ], 200);
    
    });
        
        }

        
public function destroy(etudiant $etudiant){

 return DB::transaction(function () use ($etudiant) {


    $user =$etudiant->user;
  
    $user->delete();
    $etudiant->delete(); 
       
    
    
    


    return response()->json([
        'message' => 'etudiant supprimé avec succès.',
        'etudiant' => $etudiant,
    ], 200);

});
}

}










