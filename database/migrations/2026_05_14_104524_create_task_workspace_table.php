<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_workspace', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('workspace_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Migrate existing data from tasks table to task_workspace pivot table
        $tasks = DB::table('tasks')->whereNotNull('workspace_id')->get();
        foreach ($tasks as $task) {
            DB::table('task_workspace')->insert([
                'task_id' => $task->id,
                'workspace_id' => $task->workspace_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Drop workspace_id column from tasks table
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add workspace_id column to tasks table
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('workspace_id')->nullable()->constrained()->onDelete('set null');
        });

        // Move data back (optional, but good for completeness)
        $pivotData = DB::table('task_workspace')->get();
        foreach ($pivotData as $data) {
            DB::table('tasks')->where('id', $data->task_id)->update(['workspace_id' => $data->workspace_id]);
        }

        Schema::dropIfExists('task_workspace');
    }
};
