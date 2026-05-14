<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Notification;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// NOTE: Subtask feature removed.



class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->has('date')) {
            $tasks = $user->tasks()->with('workspaces')->where('due_date', $request->date)->get();
            return response()->json($tasks);
        }

        $tasks = $user->tasks()->with('workspaces')->get();

        return response()->json($tasks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'priority' => 'required|in:Urgent,High,Normal,Low',
            'workspace_ids' => 'nullable|array',
            'workspace_ids.*' => 'exists:workspaces,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,ppt,pptx',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'priority' => $request->priority,
            'user_id' => Auth::id(),
        ]);

        if ($request->has('workspace_ids')) {
            $task->workspaces()->sync($request->workspace_ids);
        }



        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $uploadedFile) {
                if (!$uploadedFile) {
                    continue;
                }

                $path = $uploadedFile->store('task_attachments', 'public');

                $type = null;
                $mime = $uploadedFile->getClientMimeType();
                if (str_starts_with($mime, 'image/')) {
                    $type = 'image';
                } else {
                    $type = 'document';
                }

                TaskAttachment::create([
                    'task_id' => $task->id,
                    'user_id' => Auth::id(),
                    'filename' => basename($path),
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'mime_type' => $mime,
                    'size' => $uploadedFile->getSize(),
                    'storage_path' => $path,
                    'type' => $type,
                ]);
            }
        }

        $task->load(['workspaces', 'attachments']);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'task' => $task
            ]);
        }

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Auth::user()->tasks()->with(['attachments', 'workspaces'])->findOrFail($id);
        return response()->json($task);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'priority' => 'sometimes|required|in:Urgent,High,Normal,Low',
            'completed' => 'sometimes|boolean',
            'workspace_ids' => 'nullable|array',
            'workspace_ids.*' => 'exists:workspaces,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,ppt,pptx',
        ]);

        $data = $request->only(['title', 'description', 'due_date', 'start_time', 'end_time', 'priority', 'completed']);

        if ($request->has('workspace_ids')) {
            $task->workspaces()->sync($request->workspace_ids);
        }

        // Handle completed_at timestamp
        if ($request->has('completed')) {
            if ($request->completed && !$task->completed) {
                $data['completed_at'] = now();
            } elseif (!$request->completed && $task->completed) {
                $data['completed_at'] = null;
            }
        }

        $task->update($data);

        // Handle attachment removals on edit (from remove_attachments[])
        if ($request->has('remove_attachments')) {
            $removeIds = $request->input('remove_attachments', []);
            if (!is_array($removeIds)) {
                $removeIds = [$removeIds];
            }

            $attachmentsToRemove = TaskAttachment::where('task_id', $task->id)
                ->where('user_id', Auth::id())
                ->whereIn('id', $removeIds)
                ->get();

            foreach ($attachmentsToRemove as $att) {
                // delete file from storage if exists
                if (!empty($att->storage_path)) {
                    \Storage::disk('public')->delete($att->storage_path);
                }
                $att->delete();
            }
        }

        // Handle file uploads on edit (append new attachments)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $uploadedFile) {
                if (!$uploadedFile) continue;

                $path = $uploadedFile->store('task_attachments', 'public');
                $mime = $uploadedFile->getClientMimeType();
                $type = str_starts_with($mime, 'image/') ? 'image' : 'document';

                TaskAttachment::create([
                    'task_id' => $task->id,
                    'user_id' => Auth::id(),
                    'filename' => basename($path),
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'mime_type' => $mime,
                    'size' => $uploadedFile->getSize(),
                    'storage_path' => $path,
                    'type' => $type,
                ]);
            }
        }


        return response()->json(['success' => true, 'task' => $task->load(['attachments', 'workspaces'])]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        $task->delete();

        return response()->json(['success' => true]);
    }

    public function duplicate($id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        $newTask = $task->replicate();
        $newTask->title = $task->title . ' (Copy)';
        $newTask->save();

        // Duplicate attachments
        $attachments = \App\Models\TaskAttachment::where('task_id', $task->id)->get();
        foreach ($attachments as $attachment) {
            $newAttachment = $attachment->replicate();
            $newAttachment->task_id = $newTask->id;
            
            // Physically copy the file to avoid shared references
            if (!empty($attachment->storage_path) && \Storage::disk('public')->exists($attachment->storage_path)) {
                $fileExtension = pathinfo($attachment->storage_path, PATHINFO_EXTENSION);
                $newFileName = \Illuminate\Support\Str::random(40) . ($fileExtension ? '.' . $fileExtension : '');
                $newPath = 'task_attachments/' . $newFileName;
                
                \Storage::disk('public')->copy($attachment->storage_path, $newPath);
                $newAttachment->storage_path = $newPath;
                $newAttachment->filename = $newFileName;
            }
            
            $newAttachment->save();
        }

        return response()->json(['success' => true]);
    }


    /**
     * Display the schedule view with calendar and tasks.
     */
    public function schedule(Request $request)
    {
        $user = Auth::user();
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startOfMonth = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();

        $tasks = $user->tasks()->with('workspaces')->whereBetween('due_date', [$startOfMonth, $endOfMonth])->get();

        $tasksByDate = $tasks->groupBy(function($task) {
            return $task->due_date->format('Y-m-d');
        });

        // Separate queries for better performance - exclude completed tasks from allTasks
        $allTasks = $user->tasks()
            ->with('workspaces')
            ->where('completed', false)
            ->orderBy('due_date', 'asc')
            ->orderByRaw("CASE priority WHEN 'Urgent' THEN 1 WHEN 'High' THEN 2 WHEN 'Normal' THEN 3 WHEN 'Low' THEN 4 END")
            ->get();

        $todayTasks = $user->tasks()
            ->with('workspaces')
            ->where('due_date', now()->toDateString())
            ->where('completed', false)
            ->get();

        $upcomingTasks = $user->tasks()
            ->with('workspaces')
            ->where('due_date', '>', now()->toDateString())
            ->where('completed', false)
            ->get();

        $completedTasks = $user->tasks()
            ->with('workspaces')
            ->where('completed', true)
            ->get();

        $workspaces = $user->workspaces()->get();
        return view('schedule', compact('tasksByDate', 'month', 'year', 'todayTasks', 'upcomingTasks', 'completedTasks', 'allTasks', 'workspaces'));
    }

    /**
     * Generate a report of completed tasks (History).
     */
    public function historyReport()
    {
        $user = Auth::user();
        $historyTasks = $user->tasks()
            ->with('workspaces')
            ->where('completed', true)
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('reports.history', compact('historyTasks', 'user'));
    }
    public function search(Request $request)
    {
        $query = $request->get('query');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $tasks = Auth::user()->tasks()
            ->with('workspaces')
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->limit(8)
            ->get();

        return response()->json($tasks);
    }
}
