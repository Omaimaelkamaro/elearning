<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dateInscription',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function etudiant()
{
    return $this->hasOne(etudiant::class);
}

    public function formateur()
{
    return $this->hasOne(Formateur::class);
}

public function admin()
{
    return $this->hasOne(Admin::class);
}


public function inscription()
    {
        return $this->hasMany(Inscription::class);
    }
public function resultat()
    {
        return $this->hasMany(Resultat::class);

    }
//pivot apprentissage
    public function apprentissage()
    {
        return $this->hasMany(Apprentissage::class);
    }
    
    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'apprentissage')
                    ->withPivot('progression', 'etat', 'date_debut', 'dateFin', 'derniere_activite')
                    ->withTimestamps();
    }
    


public function profile()
{
    return match($this->role) {
        'etudiant' => $this->etudiant,
        'formateur' => $this->formateur,
        'administrateur' => $this->admin,
        default => null,
    };
}

}
