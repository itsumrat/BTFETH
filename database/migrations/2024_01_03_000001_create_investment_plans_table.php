<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('plan_name');           // Plan 1, Plan 2, VIP 1, VIP 2
            $table->integer('plan_level');         // 1,2,3,4 — for ordering
            $table->integer('max_cycles');         // 2,3,6,5
            $table->integer('cycle_number');       // which cycle this is (1,2,3...)
            $table->decimal('amount', 15, 2);      // invested amount
            $table->decimal('profit_rate', 5, 2);  // % per day (admin-overridable)
            $table->integer('duration_days');      // 1-7, 1-15, 1-28, 1-28
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investment_plans');
    }
};
