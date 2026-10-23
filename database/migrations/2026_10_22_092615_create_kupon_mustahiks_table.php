<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kuponmustahiks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mustahik_id')
                ->constrained('mustahiks')
                ->cascadeOnDelete();

            $table->string('qr_code')->unique();

            $table->enum('status', [
                'belum_diambil',
                'sudah_diambil',
            ])->default('belum_diambil');

            $table->foreignId('scanned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('scanned_at')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kuponmustahiks');
    }
};