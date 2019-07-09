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
            $table->date('tglmcb');
            $table->time('jammcb');
            $table->double('I1');
            $table->double('I2');
            $table->double('I3');
            $table->double('V1');
            $table->double('V2');
            $table->double('V3');
            $table->double('VAB');
            $table->double('VAC');
            $table->double('VBC');
            $table->double('PF');
            $table->double('wh');
            $table->double('kwh');
            $table->integer('blok_id')->unsigned();
            $table->foreign('blok_id')
                ->references('id')
                ->on('blok')
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
        Schema::dropIfExists('transaksi_mcb');
    }
}
