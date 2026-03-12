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
        Schema::create('eleves', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance')->nullable();
            $table->enum('genre', ['M', 'F', 'Autres'])->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('nationalite')->nullable();
            $table->text('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('groupe_sanguin')->nullable();
            $table->text('notes_medicales')->nullable();
            $table->date('date_inscription')->nullable();
            $table->string('pieces_jointes')->nullable();
            $table->string('photo')->nullable();
            $table->enum('statut', ['actif', 'suspendu', 'diplome', 'abandonne'])->default('actif');

            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('etablissement_id')->nullable()->constrained('etablissements')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
