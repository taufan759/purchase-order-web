<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('number_of_purchase_requisition');  // Mengganti order_number
            $table->string('number_of_memo_dinas');  // Mengganti supplier
            $table->date('doc_date');  // Mengganti order_date
            $table->string('description');
            $table->string('penanggung_jawab');  // Menambah kolom baru
            $table->string('id_tahun');
            $table->timestamps();
        });        
        
    }

    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
}
