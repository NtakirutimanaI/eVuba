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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->morphs('feedbackable');
            $table->integer('rating')->nullable();
            $table->integer('response_time_rating')->nullable();
            $table->integer('resolution_quality_rating')->nullable();
            $table->integer('communication_rating')->nullable();
            $table->text('comments')->nullable();
            $table->string('attachment')->nullable();
            $table->boolean('sla_compliant')->nullable();
            $table->integer('actual_completion_time')->nullable()->comment('in minutes');
            $table->integer('sla_threshold')->nullable()->comment('in minutes');
            $table->string('status')->default('pending'); // pending, submitted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
