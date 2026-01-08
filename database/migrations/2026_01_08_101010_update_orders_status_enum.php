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
        // Modify the enum column to include 'approved'
        // Note: doctrine/dbal might have issues with enums, so raw SQL is often safer for enums on MySQL
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'approved', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original
        // Be careful: if there are 'approved' records, this might truncate or error. 
        // For safety in down(), we might just leave it or map them back.
        // DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
