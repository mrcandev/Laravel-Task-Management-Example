<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;

class TasksController extends Controller
{

    public function insert(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'project' => 'required|exists:projects,id',
            'name' => 'required|string',
        ]);

        // Create a new task
        $time = time();
        $task = Task::createTask([
            'project' => $request->project,
            'name' => $request->name,
            'priority' => 0,
            'is_completed' => 0,
            'created_at' => $time,
            'updated_at' => $time,
        ]);

        // Redirect to the home index page after creating a new task
        return redirect()->route('home.index');
    }


    public function update(Request $request, $id)
    {
        // Find the relevant task instance
        $task = Task::find($id);

        if (!$task) {
            // If task not found, abort with 404 error
            abort(404, 'Task not found.');
        }

        // Validate the incoming request data
        $validatedData = $request->validate([
            'project' => 'required|exists:projects,id',
            'name' => 'required|string',
        ]);

        // Update the task properties
        $task->updateTask([
            'project' => $request->project,
            'name' => $request->name,
        ]);

        // Redirect or show a message indicating successful update
        return redirect()->route('home.index');
    }


    public function delete($id)
    {
        // Find the relevant task instance
        $task = Task::find($id);

        if (!$task) {
            // If task not found, abort with 404 error
            abort(404, 'Task not found.');
        }

        // Soft delete the task
        $task->delete();

        // Redirect or show a message indicating successful deletion
        return redirect()->route('home.index');
    }


    public function filter(Request $request)
    {
        $filters = $request->only(['project', 'is_completed', 'search']);

        $tasks = Task::getAllTasksWithFilters($filters);

        return view('home.index', compact('tasks'));
    }


    public function toggleCompletion(Request $request, $id)
    {
        // Find the relevant task instance
        $task = Task::find($id);

        if (!$task) {
            // If task not found, return 404 error
            return response()->json(['error' => 'Task not found.'], 404);
        }

        // Toggle the is_completed value
        $task->is_completed = !$task->is_completed;
        $task->save();

        // Return the updated status
        return response()->json(['success' => true, 'is_completed' => $task->is_completed]);
    }


    public function updatePriority(Request $request)
    {
        $taskOrder = $request->input('taskOrder');
        foreach ($taskOrder as $index => $taskId) {
            $task = Task::find($taskId);
            if ($task) {
                $task->update(['priority' => $index + 1]);
            }
        }

        return response()->json(['success' => true]);
    }
}
