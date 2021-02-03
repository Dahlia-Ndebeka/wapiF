<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtilisateursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('login', 100);
            $table->string('password', 255);
            $table->string('email', 150);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('photo', 100);
            $table->string('role', 100);
            $table->boolean('actif');
            $table->dateTime('date_creation');
            $table->string('nomAdministrateur', 100)->nullable();
            $table->string('prenomAdministrateur', 100)->nullable();
            $table->string('telephoneAdministrateur', 20)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('utilisateurs');
    }
}
