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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // ex: "6ème A", "Terminale S1"
            $table->integer('capacite')->default(50);
            $table->string('salle')->nullable();

            $table->foreignId('etablissement_id')->nullable()->constrained('etablissements')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('niveau_id')->nullable()->constrained('niveaux')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('annee_scolaire_id')->nullable()->constrained('annee_scolaires')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
