<?php

namespace App\Http\Controllers\cours;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
public function index(){
    $categorie = Categorie::all();
    return response()->json([

        "message"=>"voici la liste des categories",
          'categories'  =>    $categorie,
        
    ]) ;
    
}
public function store(Request $request){
    $user = auth()->user();

    if($user->role=='administrateur'){
    $validated =$request->validate([
        
        'title'=>'required|string',
        'description'=>'required|string',
    ]);

$categorie=Categorie::firstOrCreate(

    ['title' => $validated['title']], 
    ['description' => $validated['description']]
);
return response()->json([

    "message"=>"La catégorie est crée avec succés",
      'categorie'  =>    $categorie,
    
]) ;

}
return response()->json([

    "message"=>"Accées non autorisé",
      
    ]) ;
}

public function update(Request $request, $id)
{
    $user = auth()->user();
    
    if ($user->role == 'administrateur') {
        // Validate incoming data
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);
        
        // Find the category
        $categorie = Categorie::find($id);
        
        if (!$categorie) {
            return response()->json([
                'message' => 'Cette catégorie n\'existe pas',
            ], 404);  // Return 404 if category is not found
        }

        // Update category
        $updated = $categorie->update($validated);
        
        if ($updated) {
            return response()->json([
                'message' => 'Catégorie modifiée avec succès',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Échec de la mise à jour de la catégorie',
            ], 500);  // Return 500 if update fails
        }
    } else {
        return response()->json([
            'message' => 'Unauthorized',
        ], 403);  // Return 403 if user is not an admin
    }
}



public function destroy($id){

$user=auth()->user();
if($user->role=='administrateur'){

 $categorie= Categorie::find($id);
  if($categorie){
  
    $categorie->delete($id);

   return response()->json([
     'message' =>"categorie supprimé avec succés",
   ]);
 }
 else{
    return response()->json([


'message' =>"categorie n'existe pas",

    ]);
 }
}

}



}
