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
        Schema::create('moyenne_matieres', function (Blueprint $table) {
            $table->id();
            $table->decimal('moyenne')->nullable();
            $table->decimal('coefficient')->nullable();
            $table->decimal('moyenne_ponderee')->nullable();
            $table->integer('rang')->nullable();
            $table->integer('total_eleve')->nullable();
            $table->text('appreciation')->nullable();
            $table->timestamp('calculated_at')->nullable();

            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('period_evaluation_id')->constrained('period_evaluations')->onDelete('cascade');

            $table->timestamps();

            // Contrainte d'unicité : une moyenne par élève, par matière et par période d'évaluation
            $table->unique(['eleve_id', 'matiere_id', 'period_evaluation_id'], 'unique_moyenne_matiere');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moyenne_matieres');
    }
};
