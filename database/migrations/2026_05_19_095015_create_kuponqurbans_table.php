<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKuponqurbansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kuponqurbans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qurban_id')
                ->constrained('qurbans')
                ->onDelete('cascade');
            $table->string('qr_code')->unique();
            $table->enum('status', ['belum_diambil', 'sudah_diambil'])
                ->default('belum_diambil');
            $table->foreignId('scanned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('scanned_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kuponqurbans');
    }
}
