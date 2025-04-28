<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reponse_etudiant', function (Blueprint $table) {
            $table->id();
            $table->boolean('est_correcte');
            $table->foreignId('resultat_id')->nullable()->constrained('resultat');
            $table->foreignId('question_id')->constrained('question')->onDelete('cascade');
            $table->foreignId('option_reponse_id')->constrained('option_reponse')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reponse_etudiant');
    }
};
