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
        Schema::create('module', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            
            $table->string('contenu')->nullable();
            $table->integer('duree');
            $table->integer('ordre');
            $table->string('type_contenu');
            $table->foreignId('cours_id')->constrained()->onDelete('cascade');
            $table->unique(['cours_id', 'ordre']);
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
        Schema::dropIfExists('module');
    }
};
