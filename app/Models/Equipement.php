<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Equipement extends Model
{
    use HasFactory;

    protected $fillable =
        [
        'nom_appareil',
        'puissance',
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

    // relation entre compteur et equipement

    public function compteursEquipement()
    {
        return $this->belongsTo(Compteur::class, 'compteur_id');
    }
    public function UserEquipement()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
