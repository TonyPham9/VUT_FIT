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
        Schema::create('hodnocenis', function (Blueprint $table) {
            $table->id();
            $table->integer('hodnoceni');
            $table->unsignedBigInteger('id_student');
            $table->unsignedBigInteger('id_termindatum');
            $table->timestamps();

            $table->foreign('id_student')->references('id_student')->on('students')->onDelete('cascade');
            $table->foreign('id_termindatum')->references('id_termindatum')->on('termindatums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hodnocenis');
    }
};
