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
        Schema::create('mustahiks', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->text('alamat');
            $table->string('rt', 10)->nullable();
            $table->string('rw', 10)->nullable();
            $table->string('nomor_hp', 20)->nullable();

            $table->foreignId('periode_id')
                ->constrained('zakat_periodes')
                ->restrictOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['periode_id', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mustahiks');
    }
};
