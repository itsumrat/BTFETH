<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plan_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('plan_name');
            $table->integer('plan_level');
            $table->decimal('amount', 15, 2);
            $table->decimal('profit_rate', 5, 2);
            $table->integer('duration_days');
            $table->integer('cycle_number')->default(1);
            $table->integer('max_cycles');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('admin_note')->nullable();
            $table->boolean('seen_by_admin')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_requests');
    }
};
