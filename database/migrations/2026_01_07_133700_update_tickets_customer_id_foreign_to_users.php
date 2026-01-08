<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            // $table->dropForeign(['customer_id']);

            // Add the new foreign key constraint referencing users table

            // Clean up orphaned records first
            DB::table('tickets')
                ->whereRaw('customer_id NOT IN (SELECT id FROM users)')
                ->update(['customer_id' => null]);

            $table->foreign('customer_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('set null');
        });
    }
};
