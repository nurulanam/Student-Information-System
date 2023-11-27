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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('program_id');
            $table->integer('total_cost')->nullable();
            $table->string('payment_mode')->nullable()->comment('full, upfront, installment');
            $table->integer('total_installment')->nullable();
            $table->integer('installment_completed')->nullable();
            $table->decimal('total_paid', 8, 2)->default(0);
            $table->string('status')->default('disable')->comment('active, disable');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('program_id')->references('id')->on('programs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
