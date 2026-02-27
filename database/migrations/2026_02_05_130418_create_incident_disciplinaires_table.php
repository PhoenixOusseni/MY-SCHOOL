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
        Schema::create('incident_disciplinaires', function (Blueprint $table) {
            $table->id();
            $table->date('date_incident');
            $table->time('heure_incident')->nullable();
            $table->enum('type', ['inconduite', 'violence', 'insubordination', 'tricherie', 'autre']);
            $table->enum('gravité', ['mineur', 'modéré', 'sérieux', 'critique']);
            $table->text('description');
            $table->string('emplacement')->nullable();
            $table->text('temoins')->nullable(); // noms ou descriptions des témoins
            $table->text('action_pris')->nullable();
            $table->boolean('parent_notifie')->default(false);
            $table->date('date_notification')->nullable();
            $table->enum('statut', ['ouvert', 'en_cours_examen', 'résolu', 'fermé'])->default('ouvert');

            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('reported_by')->nullable()->constrained('enseignants')->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_disciplinaires');
    }
};
