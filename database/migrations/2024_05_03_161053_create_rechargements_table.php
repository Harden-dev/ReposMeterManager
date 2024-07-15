<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rechargements', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->string('user_id');
            $table->string('compteur_id');
            $table->string('valeur_energ_dispo');
            $table->string('valeur_energ_acheter');
            $table->integer('montant_recharge');
            $table->date('date_rechargement');
            $table->time('heure_rechargement');

            $table->foreign('compteur_id')->references('id')->on('compteurs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rechargements');
    }
};
