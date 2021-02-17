<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavorisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favoris', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('utilisateurs_id');
            $table->foreign('utilisateurs_id')
            ->references('id')
            ->on('utilisateurs')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->unsignedBigInteger('annonces_id');
            $table->foreign('annonces_id')
            ->references('id')
            ->on('annonces')
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
        Schema::dropIfExists('favoris');
    }
}
