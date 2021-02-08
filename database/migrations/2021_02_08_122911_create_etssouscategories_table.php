<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtssouscategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etssouscategories', function (Blueprint $table) {

            $table->increments('etablissements_id');
            $table->integer('souscategories_id');
        });

        DB::unprepared('ALTER TABLE `etssouscategories` DROP PRIMARY KEY, ADD PRIMARY KEY (  `etablissements_id` ,  `souscategories_id` )');
    }

    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etssouscategories');
    }
}
