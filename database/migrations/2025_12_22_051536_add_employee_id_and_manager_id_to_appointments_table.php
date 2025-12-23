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
            $table->foreignId('employee_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            $table->foreignId('manager_id')->nullable()->after('employee_id')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['manager_id']);
            $table->dropColumn(['employee_id', 'manager_id']);
        });
    }
};
