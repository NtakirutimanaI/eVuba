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
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->after('status');
            $table->string('source_type')->nullable()->after('priority'); // support_ticket, booking, order, message, manual
            $table->unsignedBigInteger('source_id')->nullable()->after('source_type');
            $table->boolean('auto_assigned')->default(false)->after('source_id');
            $table->timestamp('assigned_at')->nullable()->after('auto_assigned');
            $table->text('assignment_notes')->nullable()->after('assigned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['priority', 'source_type', 'source_id', 'auto_assigned', 'assigned_at', 'assignment_notes']);
        });
    }
};
