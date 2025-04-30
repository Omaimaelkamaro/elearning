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
        Schema::create('apprentissage_module', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apprentissage_id')->constrained('apprentissage')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('module')->onDelete('cascade');
            $table->boolean('est_complete')->default(false);
            $table->timestamp('date_debut')->nullable();
            $table->timestamp('date_fin')->nullable();
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
        Schema::dropIfExists('apprentissage_modules');
    }
};
