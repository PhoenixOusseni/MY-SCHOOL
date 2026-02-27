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
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->decimal('moyenne_globale')->nullable();
            $table->integer('rang')->nullable();
            $table->integer('total_eleves')->nullable();
            $table->decimal('total_points')->nullable();
            $table->decimal('total_coefficient')->nullable();
            $table->string('mention_conduite')->nullable(); // "Excellent", "Bien", "Assez Bien"
            $table->integer('absences')->default(0);
            $table->integer('justification_absences')->default(0);
            $table->integer('retards')->default(0);
            $table->text('commentaire_principal')->nullable();
            $table->text('commentaire_directeur')->nullable();
            $table->enum('status', ['brouillon', 'publie', 'envoye'])->default('brouillon');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('generated_at')->nullable();

            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('period_evaluation_id')->constrained('period_evaluations')->onDelete('cascade');

            $table->timestamps();

            // Contrainte d'unicité : un bulletin par élève et par période d'évaluation
            $table->unique(['eleve_id', 'period_evaluation_id'], 'unique_bulletin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletins');
    }
};
