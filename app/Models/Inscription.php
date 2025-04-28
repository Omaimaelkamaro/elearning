<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'cours_id',
        'statut_paiement',
        'acces',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }
}
