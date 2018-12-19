<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMcbTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mcb_transaction', function (Blueprint $table) {
            $table->increments('id');
            $table->date('datemcb');
            $table->time('timemcb');
            $table->string('current');
            $table->string('voltage');
            $table->string('power');
            $table->integer('mcb_id')->unsigned();
            $table->integer('block_id')->unsigned();
            $table->integer('category_mcb_id')->unsigned();
            $table->foreign('mcb_id')
                ->references('id')
                ->on('mcb')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('block_id')
                ->references('id')
                ->on('block')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('category_mcb_id')
                ->references('id')
                ->on('category_mcb')
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
        Schema::dropIfExists('mcb_transaction');
    }
}
