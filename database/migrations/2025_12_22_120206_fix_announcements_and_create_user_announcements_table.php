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
        // 1. Fix announcements table
        Schema::table('announcements', function (Blueprint $table) {
            if (!Schema::hasColumn('announcements', 'message')) {
                $table->text('message')->nullable()->after('title');
            }
            if (!Schema::hasColumn('announcements', 'target_role')) {
                $table->string('target_role')->nullable()->after('message');
            }
            if (!Schema::hasColumn('announcements', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('target_role');
            }
        });

        // Migrate data from content to message if content exists
        if (Schema::hasColumn('announcements', 'content')) {
            \DB::table('announcements')->update([
                'message' => \DB::raw('content')
            ]);
            
            Schema::table('announcements', function (Blueprint $table) {
                $table->dropColumn('content');
            });
        }

        // 2. Create user_announcements pivot table
        if (!Schema::hasTable('user_announcements')) {
            Schema::create('user_announcements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('announcement_id')->constrained()->onDelete('cascade');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_announcements');
        
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['message', 'target_role', 'is_active']);
        });
    }
};
