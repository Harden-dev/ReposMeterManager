<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Rechargement extends Model
{
    use HasFactory;

    protected $fillable =
        [
        'valeur_energ_dispo',
        'valeur_energ_acheter',
        'montant_recharge',
        'date_rechargement',
        'heure_rechargement',
        'compteur_id',
        'user_id'
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        // Générer un ID automatiquement lors de la création
        static::creating(function ($model) {
            $model->id = (string) Str::random(10); // Génère une chaîne de caractères aléatoire (UUID)
        });
    }

    public function userRechargement()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function compteursRecharge()
    {
        return $this->belongsTo(Compteur::class, 'compteur_id');
    }
}
