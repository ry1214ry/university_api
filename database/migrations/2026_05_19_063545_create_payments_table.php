<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('student_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->decimal('amount', 10, 2);

            $table->string('payment_type');

            $table->date('payment_date');

            $table->string('transaction_id')->unique();

            $table->enum('status', [
                'paid',
                'pending',
                'failed'
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};