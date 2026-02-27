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
        Schema::create('devoirs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['travail de maison', 'projet', 'recherche', 'lecture', 'autre']);
            $table->date('date_assignation')->nullable();
            $table->date('date_echeance')->nullable();
            $table->decimal('note_max', 5, 2)->nullable();
            $table->string('attachment')->nullable();
            $table->boolean('est_note')->default(true);

            $table->foreignId('enseignement_matiere_classe_id')->constrained('enseignement_matiere_classes')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devoirs');
    }
};
