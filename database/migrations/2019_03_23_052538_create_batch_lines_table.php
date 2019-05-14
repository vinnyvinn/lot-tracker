<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po');
            $table->string('item');
            $table->string('batch');
            $table->string('expiry_date');
            $table->string('qty');
            $table->string('status');
            $table->string('actual_batch');
            $table->string('actual_qty');
            $table->string('actual_expiry');
            $table->integer('purchase_order_id')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('batch_lines');
    }
}
