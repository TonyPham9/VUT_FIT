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
        Schema::create('students', function (Blueprint $table) {
            $table->id('id_student');
            $table->boolean('potvrzeni');
            $table->unsignedBigInteger('osobni_cislo');
            $table->unsignedBigInteger('id_kurz');
            $table->timestamps();

            $table->foreign('osobni_cislo')->references('osobni_cislo')->on('users')->onDelete('cascade');
            $table->foreign('id_kurz')->references('id_kurz')->on('kurzs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
