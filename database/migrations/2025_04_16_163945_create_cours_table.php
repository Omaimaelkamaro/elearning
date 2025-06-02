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
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('langue')->default('francais');
            $table->text('description')->nullable();
            $table->boolean('gratuit')->default(false);
            $table->date('date_de_creation');
            $table->integer('duree')->nullable();
            $table->float('prix')->nullable();
            $table->enum('niveau_de_difficulte',['avance','moyen','basique'])->default('basique');
            $table->foreignId('categorie_id')->constrained()->onDelete('cascade');
            $table->foreignId('formateur_id',)->constrained('formateur')->onDelete('cascade');
            $table->string('photo_path')->nullable();
            $table->boolean('pblished')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cours');
    }
};
