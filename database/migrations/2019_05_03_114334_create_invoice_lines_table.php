<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('OrderNum');
            $table->string('InvDate');
            $table->string('cDescription');
            $table->string('fQuantity');
            $table->integer('auto_index');
            $table->string('status');
            $table->string('item');
            $table->string('idInvoiceLines');
            $table->string('GrvNumber')->nullable();
            $table->string('cAccountName');
            $table->string('fUnitCost')->default(0);
            $table->string('fQtyToProcess')->default(0);
            $table->string('fUnitPriceExcl')->default(0);
            $table->string('iStockCodeID');
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('invoice_lines');
    }
}
