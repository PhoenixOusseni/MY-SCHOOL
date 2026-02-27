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
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->date('date_inscription');
            $table->enum('statut', ['inscrit', 'transfere', 'termine', 'abandonne'])->default('inscrit');

            $table->foreignId('eleve_id')->nullable()->constrained('eleves')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('classe_id')->nullable()->constrained('classes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('annee_scolaire_id')->nullable()->constrained('annee_scolaires')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();

            // Contrainte d'unicité : un élève ne peut être inscrit qu'une seule fois dans une classe pour une année scolaire donnée
            $table->unique(['eleve_id', 'classe_id', 'annee_scolaire_id'], 'unique_inscription');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
