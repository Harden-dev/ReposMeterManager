<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Compteur extends Model
{
    use HasFactory;

    protected $fillable =
        [
        'numero_compteur',
        'localisation',
        'type_local',
        'frequence_moy_rechargement',
        'montant_moy_rechargement',
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function equipement(): HasMany
    {
        return $this->hasMany(Equipement::class, 'compteur_id');
    }

    public function rechargement()
    {
        return $this->hasMany(Rechargement::class, 'compteur_id');
    }

    public function releve()
    {
        return $this->hasMany(Releve::class, 'compteur_id');
    }

}
