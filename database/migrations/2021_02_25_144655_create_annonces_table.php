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
            $table->date('date');
            $table->string('type', 200);
            $table->string('image_couverture');
            $table->string('lieu', 200);
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->boolean('etablissement');
            $table->string('nom_etablissement');
            $table->boolean('etat')->default(0);
            $table->boolean('actif')->default(1);

            $table->unsignedBigInteger('utilisateurs_id');
            $table->foreign('utilisateurs_id')
            ->references('id')
            ->on('utilisateurs')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->unsignedBigInteger('sous_categories_id');
            $table->foreign('sous_categories_id')
            ->references('id')
            ->on('sous_categories')
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
