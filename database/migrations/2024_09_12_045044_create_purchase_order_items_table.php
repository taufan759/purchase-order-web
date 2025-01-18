<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderItemsTable extends Migration
{
    public function up()
{
    Schema::create('purchase_order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade'); 
        $table->string('deskripsi');
        $table->integer('qty');
        $table->string('satuan');
        $table->string('hps_satuan'); 
        $table->string('hps_total');  
        $table->string('dasar_acuan_harga');
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('purchase_order_items');
    }
}
