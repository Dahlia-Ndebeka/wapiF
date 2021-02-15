<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtablissementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etablissements', function (Blueprint $table) {
            $table->id();
            $table->string('nom_etablissement', 200);
            $table->string('adresse', 200);
            $table->string('telephone', 200);
            $table->text('description');
            $table->string('heure_ouverture', 20);
            $table->string('heure_fermeture', 20);
            $table->string('email', 200);
            $table->string('boite_postale', 200);
            $table->string('site_web', 200);
            $table->string('logo', 200);
            $table->boolean('actif');
            $table->float('latitude');
            $table->float('longitude');
            
            $table->unsignedBigInteger('arrondissements_id');
            $table->foreign('arrondissements_id')
            ->references('id')
            ->on('arrondissements')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->unsignedBigInteger('utilisateurs_id');
            $table->foreign('utilisateurs_id')
            ->references('id')
            ->on('utilisateurs')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->unsignedBigInteger('categories_id');
            $table->foreign('categories_id')
            ->references('id')
            ->on('categories')
            ->onDelete('restrict')
            ->onUpdate('restrict');
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
        Schema::dropIfExists('etablissements');
    }
}
