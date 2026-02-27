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
        Schema::create('frai_scolarites', function (Blueprint $table) {
            $table->id();
            $table->string('libelle', 100); // "Inscription", "Scolarité", "Cantine"
            $table->decimal('montant', 10, 2);
            $table->string('devise')->default('XOF');
            $table->enum('frequence', ['unique', 'mensuelle', 'trimestrielle', 'annuelle'])->default('annuelle');
            $table->boolean('est_obligatoire')->default(true);

            $table->foreignId('etablissement_id')->constrained('etablissements')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frai_scolarites');
    }
};
