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
        // Rename receiver_id to recipient_id for consistency with code
        Schema::table('messages', function (Blueprint $table) {
            $table->renameColumn('receiver_id', 'recipient_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->renameColumn('recipient_id', 'receiver_id');
        });
    }
};
