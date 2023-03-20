<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kurzs', function (Blueprint $table) {
            $table->id('id_kurz');
            $table->string('zkratka');
            $table->string('nazev');
            $table->integer('pocet_mist');
            $table->integer('kredity');
            $table->string('typ');
            $table->string('jazyk');
            $table->string('zpusob_zakonceni');
            $table->string('popis');
            $table->float('cena', 8, 2);
            $table->boolean('schvaleno');
            $table->boolean('auto_add');
            $table->unsignedBigInteger('garant');
            $table->timestamps();

            $table->foreign('garant')->references('osobni_cislo')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kurzs');
    }
};
