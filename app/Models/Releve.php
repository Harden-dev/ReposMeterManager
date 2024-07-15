<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Releve extends Model
{
    use HasFactory;

    protected $fillable =
        [
        'valeur_dispo_compteur',
        'date_releve',
        'heure_releve',
        'user_id',
        'compteur_id'
    ];

    public function compteursReleve()
    {
        return $this->belongsTo(Releve::class, 'compteur_id');
    }

    public function usersReleve()
    {
        return $this->belongsTo(Releve::class, 'user_id');
    }

}
