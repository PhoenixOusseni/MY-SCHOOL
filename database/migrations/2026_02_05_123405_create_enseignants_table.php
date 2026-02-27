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
        Schema::create('enseignants', function (Blueprint $table) {
            $table->id();
            $table->string('numero_employe')->unique();
            $table->string('prenom');
            $table->string('nom');
            $table->date('date_naissance')->nullable();
            $table->enum('sexe', ['M', 'F', 'Autre'])->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->text('adresse')->nullable();
            $table->string('photo')->nullable();
            $table->string('qualification')->nullable(); // diplômes
            $table->string('specialisation')->nullable(); // spécialité
            $table->date('date_embauche')->nullable();
            $table->enum('statut', ['actif', 'en_conge', 'retraite', 'termine'])->default('actif');

            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->onDelete('set null');
            $table->foreignId('etablissement_id')->constrained('etablissements')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
};
