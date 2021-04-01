<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendriers', function (Blueprint $table) {
            $table->id();
            $table->string('label', 150)->nullable();
            $table->date('date');
            $table->string('heure_debut');
            $table->string('heure_fin');

            $table->unsignedBigInteger('annonces_id')->index();
            $table->foreign('annonces_id')
            ->references('id')
            ->on('annonces')
            ->onDelete('cascade');
            
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
        Schema::dropIfExists('calendriers');
    }
}
