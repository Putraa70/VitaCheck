<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('program_studi', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->foreignUuid('fakultas_id')->constrained('fakultas')->cascadeOnDelete();
            $t->string('kode')->unique();
            $t->string('nama');
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('program_studi');
    }
};
