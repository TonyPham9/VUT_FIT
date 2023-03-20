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
        Schema::create('lektors', function (Blueprint $table) {
            $table->id('id_lektor');
            $table->unsignedBigInteger('osobni_cislo');
            $table->unsignedBigInteger('id_termin');
            $table->timestamps();

            $table->foreign('osobni_cislo')->references('osobni_cislo')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('lektors');
    }
};
