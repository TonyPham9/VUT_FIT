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
        Schema::create('termins', function (Blueprint $table) {
            $table->id('id_termin');
            $table->string('nazev');
            $table->string('typ');
            $table->string('popis');
            $table->time('od');
            $table->time('do');
            $table->integer('den');
            $table->string('skupina');
            $table->integer('kapacita');
            $table->unsignedBigInteger('id_kurz');
            $table->unsignedBigInteger('id_mistnost');
            $table->timestamps();

            $table->foreign('id_kurz')->references('id_kurz')->on('kurzs')->onDelete('cascade');
            $table->foreign('id_mistnost')->references('id_mistnost')->on('mistnosts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('termins');
    }
};
