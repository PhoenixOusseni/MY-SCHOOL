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
        Schema::create('detail_bulletins', function (Blueprint $table) {
            $table->id();
            $table->decimal('moyenne')->nullable();
            $table->decimal('coefficient')->nullable();
            $table->decimal('moyenne_ponderee')->nullable();
            $table->decimal('moyenne_classe')->nullable();
            $table->decimal('point_min')->nullable();
            $table->decimal('point_max')->nullable();
            $table->integer('rang')->nullable();
            $table->text('appreciation')->nullable();
            $table->text('commentaire_enseignant')->nullable();

            $table->foreignId('bulletin_id')->constrained('bulletins')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('enseignant_id')->nullable()->constrained('enseignants')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_bulletins');
    }
};
