<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtablissementsSousCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etablissements_sous_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('etablissements_id')->index();
            $table->foreign('etablissements_id')
            ->references('id')
            ->on('etablissements')
            ->onDelete('cascade');

            $table->unsignedBigInteger('sous_categories_id')->index();
            $table->foreign('sous_categories_id')
            ->references('id')
            ->on('sous_categories')
            ->onDelete('cascade');

            $table->primary(['etablissements_id', 'sous_categories_id']);

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
        Schema::dropIfExists('etablissements_sous_categories');
    }
    
}
