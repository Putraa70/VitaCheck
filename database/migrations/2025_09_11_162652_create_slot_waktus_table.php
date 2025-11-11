<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('slot_waktu', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->foreignUuid('jenis_tes_id')->constrained('jenis_tes')->cascadeOnDelete();
            $t->date('tanggal');
            $t->time('mulai');
            $t->time('selesai');
            $t->unsignedInteger('kuota')->default(20);
            $t->unsignedInteger('terpesan')->default(0);
            $t->timestamps();
            $t->unique(['jenis_tes_id', 'tanggal', 'mulai', 'selesai'], 'unik_slot_waktu');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('slot_waktu');
    }
};
