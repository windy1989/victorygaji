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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->nullable();
            $table->string('rekening_bca')->nullable();
            $table->string('bulan',15)->nullable();
            $table->string('jabatan')->nullable();
            $table->string('status')->nullable();
            $table->double('gaji_pokok')->nullable();
            $table->double('jumlah_lembur_senin_jumat')->nullable();
            $table->double('total_lembur_senin_jumat')->nullable();
            $table->double('jumlah_lembur_sabtu_minggu')->nullable();    
            $table->double('total_lembur_sabtu_minggu')->nullable();
            $table->double('jumlah_lembur_inap_efektif')->nullable();    
            $table->double('total_lembur_inap_efektif')->nullable();
            $table->double('jumlah_lembur_inap_weekend')->nullable();    
            $table->double('total_lembur_inap_weekend')->nullable();
            $table->double('jumlah_potongan_terlambat')->nullable();    
            $table->double('total_potongan_terlambat')->nullable();
            $table->double('total_potongan_kehadiran')->nullable();
            $table->double('total_potongan_pinjaman')->nullable();
            $table->double('total_potongan_denda')->nullable();
            $table->double('total_potongan_bpjs')->nullable();
            $table->double('jumlah_transfer')->nullable();
            $table->timestamps();
            $table->index(['nik']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
