<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiMcbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_mcb', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('blok_id')->unsigned();
            $table->foreign('blok_id')
                ->references('id')
                ->on('blok')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu');
            $table->double('va');
            $table->double('vb');
            $table->double('vc');
            $table->double('vab');
            $table->double('vbc');
            $table->double('vca');
            $table->double('ia');
            $table->double('ib');
            $table->double('ic');
            $table->double('pa');
            $table->double('pb');
            $table->double('pc');
            $table->double('pt');
            $table->double('pfa');
            $table->double('pfb');
            $table->double('pfc');
            $table->double('ep');
            $table->double('eq');
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
        Schema::dropIfExists('transaksi_mcb');
    }
}
