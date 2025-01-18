<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drtus', function (Blueprint $table) {
            $table->id();
            $table->date('doc_date');
            $table->string('number_of_purchase_requisition');
            $table->string('number_of_memo_dinas');
            $table->string('metode_pengadaan');
            $table->text('description');
            $table->string('penanggung_jawab');
            $table->string('id_tahun');
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drtus');
    }
};
