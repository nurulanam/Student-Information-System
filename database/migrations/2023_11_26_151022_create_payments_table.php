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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->integer('installment_number');
            $table->decimal('amount_paid', 8, 2);
            $table->string('payment_type')->comment('cash, bank_transfer, direct_debit, credit_card');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('enrollment_id')->references('id')->on('enrollments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
