<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMcbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mcb', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mcb_name');
            $table->integer('specification_mcb_id')->unsigned();
            $table->foreign('specification_mcb_id')
                ->references('id')
                ->on('specification_mcb')
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
        Schema::dropIfExists('mcb');
    }
}
