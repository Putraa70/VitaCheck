<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('pemesanan_id');
            $t->string('gateway')->index();      // midtrans
            $t->string('order_id')->unique();    // pakai kode pemesanan
            $t->string('tipe')->nullable();      // qris|va|cc|gopay|...
            $t->unsignedInteger('jumlah');
            $t->string('status');                // pending|settlement|expire|cancel|deny|capture
            $t->json('payload')->nullable();
            $t->timestamps();

            $t->foreign('pemesanan_id')->references('id')->on('pemesanans')->cascadeOnDelete();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
