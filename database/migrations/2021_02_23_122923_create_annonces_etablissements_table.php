<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnoncesEtablissementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annonces_etablissements', function (Blueprint $table) {
            
            $table->unsignedBigInteger('etablissements_id')->index();
            $table->foreign('etablissements_id')
            ->references('id')
            ->on('etablissements')
            ->onDelete('cascade');

            $table->unsignedBigInteger('annonces_id')->index();
            $table->foreign('annonces_id')
            ->references('id')
            ->on('annonces')
            ->onDelete('cascade');

            $table->primary(['etablissements_id', 'annonces_id']);

        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('annonces_etablissements');
    }
}
