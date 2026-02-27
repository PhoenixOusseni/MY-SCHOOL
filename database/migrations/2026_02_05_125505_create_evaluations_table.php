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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->date('date_examen')->nullable();
            $table->time('heure_debut')->nullable();
            $table->integer('duree')->nullable(); // en minutes
            $table->string('salle')->nullable();
            $table->decimal('note_max')->nullable();
            $table->decimal('coefficient')->default(1);
            $table->text('instructions')->nullable();
            $table->boolean('est_publie')->default(false);
            $table->enum ('type', ['examen', 'controle', 'interrogation', 'devoir_sur_table', 'autre'])->default('autre')->index('type');

            $table->foreignId('enseignement_matiere_classe_id')->constrained('enseignement_matiere_classes')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreignId('typ_evaluation_id')->nullable()->constrained('typ_evaluations')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('period_evaluation_id')->constrained('period_evaluations')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
