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
        Schema::create('termindatums', function (Blueprint $table) {
            $table->id('id_termindatum');
            $table->string('typ');
            $table->string('popis');
            $table->integer('minbody');
            $table->integer('maxbody');
            $table->date('datum');
            $table->unsignedBigInteger('id_termin');
            $table->timestamps();

            $table->foreign('id_termin')->references('id_termin')->on('termins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('termindatums');
    }
};
