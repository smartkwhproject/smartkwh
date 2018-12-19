<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecificationMcbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specification_mcb', function (Blueprint $table) {
            $table->increments('id');
            $table->string('colour');
            $table->string('weight');
            $table->string('healty_status');
            $table->string('max_voltage');
            $table->string('power_factor');
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
        Schema::dropIfExists('specification_mcb');
    }
}
