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
        Schema::create('enseignement_matiere_classes', function (Blueprint $table) {
            $table->id();
            $table->integer('heure_par_semaine')->default(1);

            $table->foreignId('enseignant_id')->constrained('enseignants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('annee_scolaire_id')->constrained('annee_scolaires')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();

            // Contrainte d'unicité : un enseignant ne peut enseigner qu'une seule fois une matière dans une classe pour une année donnée
            $table->unique(['enseignant_id', 'matiere_id', 'classe_id', 'annee_scolaire_id'], 'unique_enseignement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignement_matiere_classes');
    }
};
