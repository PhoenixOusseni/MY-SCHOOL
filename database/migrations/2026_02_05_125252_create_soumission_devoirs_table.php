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
        Schema::create('soumission_devoirs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date_submission')->nullable();
            $table->text('content')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status', ['en cours', 'soumis', 'en retard', 'noté'])->default('en cours');
            $table->decimal('score')->nullable();
            $table->text('feedback')->nullable();

            $table->foreignId('graded_by')->nullable()->constrained('enseignants')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('devoir_id')->constrained('devoirs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soumission_devoirs');
    }
};
