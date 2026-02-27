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
        Schema::create('sanctions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['avertissement', 'detention', 'suspension', 'expulsion', 'autre']);
            $table->text('description');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->integer('duree')->nullable(); // en jours

            $table->foreignId('imposed_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('incident_disciplinaire_id')->constrained('incident_disciplinaires')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade')->onUpdate('cascade');

            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanctions');
    }
};
