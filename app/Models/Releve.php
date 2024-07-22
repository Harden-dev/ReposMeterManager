<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Releve extends Model
{
    use HasFactory;

    protected $fillable =
        [
        'valeur_dispo_compteur',
        'date_releve',
        'heure_releve',
        'user_id',
        'compteur_id',
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

    public function compteursReleve()
    {
        return $this->belongsTo(Releve::class, 'compteur_id');
    }

    public function usersReleve()
    {
        return $this->belongsTo(Releve::class, 'user_id');
    }

}
