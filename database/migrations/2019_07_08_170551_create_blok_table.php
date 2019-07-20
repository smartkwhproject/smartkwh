<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blok', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_blok');
            $table->string('deskripsi');
            $table->integer('gedung_id')->unsigned();
            $table->foreign('gedung_id')
                ->references('id')
                ->on('gedung')
                ->onUpdate('cascade')
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
        Schema::dropIfExists('blok');
    }
}
