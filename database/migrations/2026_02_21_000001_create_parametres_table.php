<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            $table->string('cle')->unique();          // ex: app_nom, app_devise
            $table->text('valeur')->nullable();        // valeur sérialisée si besoin
            $table->string('groupe')->default('general'); // general | notifications | securite
            $table->string('type')->default('text');   // text | boolean | select | email | color | number
            $table->string('libelle');                 // label affiché à l'utilisateur
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};
