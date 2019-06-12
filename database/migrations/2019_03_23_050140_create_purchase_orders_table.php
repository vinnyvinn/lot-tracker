<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('OrderNum');
            $table->string('InvDate');
            $table->string('cAccountName')->nullable();
            $table->string('OrdTotExcl')->nullable()->default(0);
            $table->string('OrdTotIncl')->nullable()->default(0);
            $table->string('OrdTotTax')->nullable()->default(0);
            $table->string('fQuantity')->nullable();
            $table->string('type')->default('NEW')->nullable();
            $table->integer('auto_index');
            $table->string('status');
            $table->integer('open_balance')->default(0);
            $table->string('state')->default(App\PurchaseOrder::NOT_RECEIVED_STATE);
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
        Schema::dropIfExists('purchase_orders');
    }
}
