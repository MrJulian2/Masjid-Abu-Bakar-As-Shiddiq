<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('muzakkis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('zakat_periodes')->restrictOnDelete();
            $table->enum('kategori', ['setempat', 'luar']);
            $table->text('alamat');
            $table->unsignedTinyInteger('rt')->nullable();
            $table->unsignedTinyInteger('rw')->nullable();
            $table->integer('total_jiwa')->default(0);
            $table->decimal('total_beras', 10, 2)->default(0);
            $table->bigInteger('total_uang')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muzakkis');
    }
};
