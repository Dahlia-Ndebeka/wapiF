<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtablissementAnnoncesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etablissement_annonces', function (Blueprint $table) {
            $table->integer('etablissements_id')->unsigned();
            
            $table->integer('annonces_id')->unsigned();

            $table->primary(['etablissements_id', 'annonces_id']);

            $table->foreign('etablissements_id')->references('id')->on('etablissements');

            $table->foreign('annonces_id')->references('id')->on('annonces');

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
        Schema::dropIfExists('etablissement_annonces');
    }

}