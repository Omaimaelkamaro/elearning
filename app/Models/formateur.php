<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class formateur extends Model
{
    use HasFactory;
    protected $table = 'formateur';

    protected $fillable = [
        'user_id',
        'specialite',
        'verification_statut',
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cours()
    {
        return $this->hasMany(Cours::class); 
    }
}
