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
        Schema::create('matiere_niveaux', function (Blueprint $table) {
            $table->id();
            $table->decimal('coefficient')->default(1.0);
            $table->integer('heure_par_semaine')->default(1);
            $table->boolean('is_optional')->default(false);

            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('niveau_id')->constrained('niveaux')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();

            // Contrainte d'unicité : une matière ne peut être associée qu'une seule fois à un niveau
            $table->unique(['matiere_id', 'niveau_id'], 'unique_matiere_niveau');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matiere_niveaux');
    }
};
