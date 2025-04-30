<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{   //Affichez la liste des utilisateurs 

    public function index()
    {
        $users = User::all();
        return response()->json([
            'users' => $users
        ]);

        // return view('users.index', compact('users'));
    }
    
    //Ajouter un nouveau utilisateur à la base de données

    public function store(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:etudiant,formateur,administrateur',
        'dateInscription' => 'nullable|datedate',
        ]);
        return DB::transaction(function () use ($validated,$request) {
        $user= User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            // 'dateInscription' => $validated['dateInscription'],
            'password' => Hash::make($validated['password']),
        ]);
        switch ($validated['role']) {
            case 'etudiant':
                $user->etudiant()->create();
                break;
                
            case 'formateur':
                $user->formateur()->create([
                    
                    'specialite'=> $request->specialite,
                    'verification_statut'=> $request->verification_statut,
                ]);
                
                break;
                
            case 'administrateur':
                $user->admin()->create(['niveau_acces'=> $request->niveau_acces]);
                break;
        }
        

        // return redirect()->route('users.index')
        //     ->with('success', 'User created successfully.');
        return response()->json([
            'message' => 'Utilisateur créé avec succès.',
            'user' => $user,
        ], 201); 
    });
        
      
    }
    
    //Modifier les informations d'un utilisateur par son id

    public function update(Request $request, $id)
    {
      $userAuth=auth()->user();

        $param = [
        'name' => 'required|string|max:255',
        'email' => ['required','string','email','max:255',Rule::unique('users')->ignore($request->id)],
        'password'=>'required|string|max:255',
    ];
   
        
        
        if($userAuth->role=='administrateur'){
        $param['role']='required|in:etudiant,formateur,administrateur';

    }


    $validated=$request->validate($param);

        return DB::transaction(function () use ($validated,$request,$userAuth,$id) {
            
            $user = User::findOrFail($id);
            $oldRole=$user->role;
            $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $userAuth->role==='administrateur'? $validated['role'] : $oldRole,
        ]);
            
     
        if ($userAuth->role=='administrateur'&& $user->wasChanged('role')) {
            // Supprimer les anciennes relations
            $user->etudiant()->delete();
            $user->formateur()->delete();
            $user->admin()->delete();

            // Créer la nouvelle relation
            switch ($validated['role']) {
                case 'etudiant':
                    $user->etudiant()->create();
                    break;
                    
                case 'formateur':
                    $user->formateur()->create([
                        
                        'specialite'=> $request->specialite,
                        'verification_statut'=> $request->verification_statut,
                    ]);
                    
                    break;
                    
                case 'administrateur':
                    $user->admin()->create(['niveau_acces'=> $request->niveau_acces]);
                    break;
            }}
   
        // return redirect()->route('users.index')
        //     ->with('success', 'user updated successfully.');
        return response()->json([
            'message' => 'Utilisateur modifié avec succès.',
            'user' => $user,
        ], 200);
            
        });
    }
    
     
      //supprimer un utilisateur par son id
    public function destroy($id) {

    return DB::transaction(function () use ($id) {

        $user = User::find($id);
        $user->delete();
        switch ($user['role']) {

         case "etudiant": 
            $user->etudiant()->delete();
            break;

        case "formateur": 
            $user->formateur()->delete(); 
            break;

         case "administrateur": 
            $user->admin()->delete();  

       break;
        
        
        }


        return response()->json([
            'message' => 'Utilisateur supprimé avec succès.',
            'user' => $user,
        ], 200);
    
        });   
     }

    //Afficher la formulaire de création d'un utilisateur
    public function create()
    {
        return view('users.create');

    }


     //afficher un utilisateur spécifique par son nom
    public function show($id)
    {
        $user = User::find($id);
        $user = auth()->user();
        return view('users.show', compact('user'));
    }
//afficher formulaire spécifique au modification des données d'un utilisateur

public function edit($id)
    {
        $user = User::find($id);
      
        return view('users.edit', compact('user'));
    }

}

