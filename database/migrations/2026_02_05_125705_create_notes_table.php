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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->decimal('score', 5, 2)->nullable();
            $table->decimal('max_score', 5, 2);
            $table->decimal('percentage', 5, 2)->nullable(); // pourcentage calculé
            $table->boolean('is_absent')->default(false);
            $table->boolean('absence_justified')->default(false);
            $table->text('comment')->nullable();

            $table->foreignId('entered_by')->nullable()->constrained('enseignants')->onDelete('set null');
            $table->foreignId('evaluation_id')->constrained('evaluations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamp('entered_at')->nullable();
            $table->timestamps();

            // Contrainte d'unicité : un élève ne peut avoir qu'une seule note par examen
            $table->unique(['evaluation_id', 'eleve_id'], 'unique_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
