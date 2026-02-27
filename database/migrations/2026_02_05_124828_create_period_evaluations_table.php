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
        Schema::create('period_evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('libelle'); // "Trimestre 1", "Semestre 1"
            $table->enum('type', ['trimester', 'semester', 'quarter']);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('order_index')->nullable();

            $table->foreignId('annee_scolaire_id')->constrained('annee_scolaires')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_evaluations');
    }
};
