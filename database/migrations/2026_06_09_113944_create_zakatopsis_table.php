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
        Schema::create('zakatopsis', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['beras', 'uang']);
            $table->foreignId('periode_id')->constrained('zakat_periodes')->restrictOnDelete();
            $table->decimal('nilai_beras', 5, 2)->nullable();
            $table->bigInteger('nilai_uang')->nullable();

            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakatopsis');
    }
};
