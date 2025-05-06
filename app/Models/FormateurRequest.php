<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Etudiant;

class FormateurRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'persuasion',
        'status',
        'motif_rejet',
    ];


    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

}
