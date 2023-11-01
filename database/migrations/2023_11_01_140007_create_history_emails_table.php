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
        Schema::create('history_emails', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payroll_id')->nullable();
            $table->timestamp('date_process')->nullable();
            $table->string('status',50)->nullable();
            $table->timestamps();
            $table->index(['payroll_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_emails');
    }
};
