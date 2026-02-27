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
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('periode')->nullable(); // "Matin", "Après-midi", "Cours 1-2"
            $table->boolean('is_justified')->default(false);
            $table->string('justification_document')->nullable();
            $table->text('raison')->nullable();
            $table->timestamp('reported_at')->nullable();

            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('matiere_id')->nullable()->constrained('matieres')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('reported_by')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
