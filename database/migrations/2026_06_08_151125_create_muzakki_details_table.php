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
        Schema::create('muzakki_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('muzakki_id')->constrained('muzakkis')->cascadeOnDelete();
            $table->string('nama');
            $table->enum('jenis_zakat', ['beras', 'uang']);
            $table->decimal('berat_beras', 5, 2)->nullable()->comment('Diisi jika jenis zakat = beras');
            $table->bigInteger('nominal_uang')->nullable()->comment('Diisi jika jenis zakat = uang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muzakki_details');
    }
};
