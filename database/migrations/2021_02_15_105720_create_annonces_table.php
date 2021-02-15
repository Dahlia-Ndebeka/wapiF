<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnoncesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 100);
            $table->text('description');
            $table->string('etat', 100);
            $table->date('date');
            $table->string('type', 200);
            $table->string('image_couverture');
            $table->string('lieu', 200);
            $table->boolean('actif');
            

            $table->unsignedBigInteger('utilisateurs_id');
            $table->foreign('utilisateurs_id')
            ->references('id')
            ->on('utilisateurs')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->unsignedBigInteger('etablissements_id');
            $table->foreign('etablissements_id')
            ->references('id')
            ->on('etablissements')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->unsignedBigInteger('souscategories_id');
            $table->foreign('souscategories_id')
            ->references('id')
            ->on('souscategories')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->unsignedBigInteger('calendriers_id');
            $table->foreign('calendriers_id')
            ->references('id')
            ->on('calendriers')
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
        Schema::dropIfExists('annonces');
    }
}
