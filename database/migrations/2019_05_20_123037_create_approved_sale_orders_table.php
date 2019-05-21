<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovedSaleOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('sqlsrv2')->create('approved_sale_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po');
            $table->string('item');
            $table->string('batch');
            $table->string('expiry_date');
            $table->string('qty');
            $table->string('status');
            $table->boolean('posted')->default(0);
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
        Schema::dropIfExists('approved_sale_orders');
    }
}
