<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprentissageModule extends Model
{
    protected $table = 'Apprentissage_module';

    protected $fillable = [
        'apprentissage_id', 
        'module_id', 
        'est_complete', 
        'date_debut', 
        'date_fin'
    
    
    ];
    public function apprentissage()
    {
        return $this->belongsTo(Apprentissage::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
