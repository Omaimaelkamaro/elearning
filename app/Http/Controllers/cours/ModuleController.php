<?php

namespace App\Http\Controllers\cours;
use App\Models\module;
use App\Models\cours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModuleController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            
            
            if (!in_array($this->user->role, ['administrateur', 'formateur'])) {
                abort(403, 'Accès non autorisé');
            }
            
            return $next($request);
        });
    }

    
    protected function checkFormateurPermissions($coursId)
    {
        
        if ($this->user->role === 'administrateur') {
            abort(403, 'Action non autorisée pour les administrateurs');
        }

        
        Cours::where('id', $coursId)
            ->where('formateur_id', $this->user->formateur->id)
            ->firstOrFail();
    }



    public function index($coursId){

       
         
        if ($this->user->role === 'formateur') {
            Cours::where('id', $coursId)
                ->where('formateur_id', $this->user->formateur->id)
                ->firstOrFail();
        }

        

        $modules=Module::where('cours_id',$coursId)->get();
        return response()->json([
        'message'=>'voici la liste des module cogncernant ce cours ',
         'Module'=>$modules,

       ]);


    }

public function store(Request $request,$coursId){

    
    $this->checkFormateurPermissions($coursId);


  
  $validated=$request->validate([

    'title'=>'required|string',
    'contenu'=>'nullable|string',
    'duree'=>'nullable|Integer',
    'type_contenu' => 'nullable|in:text,video,pdf',
    'ordre'=>'nullable|Integer|min:1',
    Rule::unique('module')->where(function ($query) use ($coursId) {
        return $query->where('cours_id', $coursId);
       
    })
    

]);



$dernierOrdre = Module::where('cours_id', $coursId)->max('ordre');


$validated['ordre'] = ++$dernierOrdre ;



$module=Module::create(['title' => $validated['title'],
        'contenu' => $validated['contenu']?? null,
        'duree' => $validated['duree']?? null,
        'ordre' => $validated['ordre']?? null,
        'type_contenu' => $validated['type_contenu']?? null,
        'cours_id' => $coursId,
    ]);

return response()->json([
    'message'=>' module crée avec succés  ',
     'Module'=>$module,
]);

}


public function update(Request $request, $coursId, $moduleId)
{
    $this->checkFormateurPermissions($coursId);

    $rules = [
        'title' => 'required|string',
        'duree' => 'required|integer',
        'ordre' => [
            'required',
            'integer',
            'min:1',
            Rule::unique('module')->where(function ($query) use ($coursId) {
                return $query->where('cours_id', $coursId);
            })->ignore($moduleId)
        ],
        'type_contenu' => 'nullable|in:text,video,pdf',
    ];

    if ($request->type_contenu === 'text') {
        $rules['contenu'] = 'required|string';
    } elseif ($request->type_contenu === 'video') {
        $rules['contenu'] = 'required|file|mimes:mp4,mov,avi|max:51200'; // 50MB max
    } elseif ($request->type_contenu === 'pdf') {
        $rules['contenu'] = 'required|file|mimes:pdf|max:10240'; // 10MB max
    }

    $validated = $request->validate($rules);

    // Gérer les fichiers uploadés
    if ($request->hasFile('contenu')) {
        $file = $request->file('contenu');
        $filename = time() . '_' . $file->getClientOriginalName();

        if ($request->type_contenu === 'pdf') {
            $file->storeAs('modules', $filename, 'public');
            $validated['contenu'] = 'storage/modules/' . $filename;
        } elseif ($request->type_contenu === 'video') {
            $file->storeAs('videos', $filename, 'public');
            $validated['contenu'] = 'storage/videos/' . $filename;
        }
    }

    $validated['type_contenu'] = $request->type_contenu;

    $module = Module::where('cours_id', $coursId)
                    ->findOrFail($moduleId);

    $module->update($validated);

    return response()->json([
        'message' => 'Module modifié avec succès',
        'module' => $module,
    ]);
   

}



public function destroy($coursId, $moduleId)
{
    $this->checkFormateurPermissions($coursId);
   
    $module = Module::withTrashed()->where('id', $moduleId)
        ->where('cours_id', $coursId)
        ->firstOrFail();

    $deletedOrdre = $module->ordre;

    $module->forceDelete();

    // Décrémenter l’ordre de tous les modules qui viennent après
    Module::where('cours_id', $coursId)
        ->where('ordre', '>', $deletedOrdre)
        ->decrement('ordre');

    return response()->json([
        'message' => 'Module supprimé avec succès',
        'module' => $module,
    ]);
}

}
