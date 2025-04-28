<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'Paiement';

    protected $fillable = [
        'inscription_id',
        'methode',
        'preuve',
        'montant',
    ];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
}
}