<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('plan_name');                    // Plan 1, Plan 2, VIP 1, VIP 2
            $table->decimal('amount', 15, 2);               // deposited amount
            $table->decimal('profit_percent', 5, 2);        // e.g. 1.5, 3.2, 5.0, 7.0 (admin can override)
            $table->integer('duration_days');               // total duration in days
            $table->integer('total_cycles');                // total cycles
            $table->integer('completed_cycles')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
