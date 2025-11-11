<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jenis_tes', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->string('kode')->unique(); // UMUM, NARKOBA, BUTA_WARNA
            $t->string('nama');
            $t->text('deskripsi')->nullable();
            $t->unsignedInteger('biaya')->nullable();
            $t->boolean('aktif')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('jenis_tes');
    }
};
