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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant', 10, 2);
            $table->decimal('reste_a_payer', 10, 2);
            $table->date('date_paiement');
            $table->enum('methode_paiement', ['Especes', 'cheque', 'virement', 'mobile_money', 'carte']);
            $table->string('reference')->nullable();
            $table->enum('status', ['En attente', 'Terminé', 'Annulé', 'Remboursé'])->default('Terminé');
            $table->text('notes')->nullable();

            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('frai_scolarite_id')->constrained('frai_scolarites')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('annee_scolaire_id')->constrained('annee_scolaires')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
