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
        Schema::table('cours', function (Blueprint $table) {
            $table->unsignedBigInteger('formateur_id')->after('categorie_id');
    
            $table->foreign('formateur_id')
                  ->references('id')->on('formateur')
                  ->onDelete('cascade'); // ou set null si tu veux garder le cours sans formateur
        });
    }
    
    public function down()
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->dropForeign(['formateur_id']);
            $table->dropColumn('formateur_id');
        });
    }
    
};
