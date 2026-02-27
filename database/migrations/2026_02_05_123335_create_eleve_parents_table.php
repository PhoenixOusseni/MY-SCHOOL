<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('eleve_parents', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_primary')->default(false); // Indique si c'est le tuteur principal
            $table->boolean('can_pickup')->default(true); // Indique si le parent peut venir chercher l'élève
            $table->boolean('emergency_contact')->default(false); // Indique si le parent est un contact d'urgence

            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('tuteur_id')->constrained('tuteurs')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eleve_parents');
    }
};
