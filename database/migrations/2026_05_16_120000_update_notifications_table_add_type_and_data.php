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
        Schema::table('notifications', function (Blueprint $table) {
            // Drop old columns if they don't exist or re-add them safely
            if (!Schema::hasColumn('notifications', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('notifications', 'title')) {
                $table->string('title');
            }
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message');
            }
            if (!Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false);
            }
            // New columns for notification types and metadata
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type')->default('general'); // 'due_reminder', 'workspace_invitation', 'general'
            }
            if (!Schema::hasColumn('notifications', 'data')) {
                $table->json('data')->nullable(); // { task_id, workspace_id, invitation_id, action_url }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['type', 'data']);
        });
    }
};
