<?php

namespace App\Http\Controllers;

use App\Models\admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use PhpParser\Node\Stmt\For_;

class AdminController extends Controller
{

 public function index(){
    
    $admins = admin::all();
    return response()->json([

        "message"=>"voici la liste des administrateurs",
          'admins'  =>    $admins,
        
    ]) ;


  }


    public function store(Request $request){
     
       $validated= $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:administrateur',
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


$user->admin()->create([
    'niveau_acces'=> $request->niveau_acces,
  
]);

return response()->json([
    'message' => 'administrateur créé avec succès.',
    'user' => $user,
], 201); 
 });
    
}


public function update(Request $request,admin $admin){
    $user=$admin->user;

$validated=$request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|',Rule::unique('users')->ignore($request->id),
        
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:etudiant,formateur,administrateur',
        'dateInscription' => 'nullable|date',
        'niveau_acces'=>'required|integer',

]);
return DB::transaction(function () use ($validated,$request,$admin,$user) {

    $user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        // 'dateInscription' => $validated['dateInscription'],
    ]);

    $admin->update([
        'niveau_acces' => $validated['niveau_acces'],
       
    ]);
    

    
    //au cas de changement du role
    if ($user->wasChanged('role')) {
        $admin->delete();
        
         switch ($validated['role']) {
            case 'etudiant':
                $user->etudiant()->create();
                break;
                
                case 'formateur':
                    $user->formateur()->create([
                        
                        'specialite'=> $request->specialite,
                        'verification_statut'=> $request->verification_statut,
                    ]);
        }}

    // return redirect()->route('users.index')
    //     ->with('success', 'user updated successfully.');
    return response()->json([
        'message' => 'administrateur modifié avec succès.',
        'admin' => $user,
    ], 200);
    
    });
        
        }

        
public function destroy(admin $admin){

 return DB::transaction(function () use ($admin) {


    $user =$admin->user;
  
    $user->delete();
    $admin->delete(); 
       
    
    
    


    return response()->json([
        'message' => 'administrateur supprimé avec succès.',
        'admin' => $admin,
    ], 200);

});
}

}










