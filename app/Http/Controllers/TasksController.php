<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;

class TasksController extends Controller
{
  public function store(Request $request, Task $task)
  {
    $validated = $request->validate([
      'project' => 'required|exists:projects,id',
      'name' => 'required|string',
    ]);

    $task->create([
      'project' => $validated['project'],
      'name' => $validated['name'],
      'priority' => 0,
      'is_completed' => 0,
    ]);

    return redirect()->route('home.index');
  }


  public function update(Request $request, Task $task)
  {
    $validated = $request->validate([
      'project' => 'required|exists:projects,id',
      'name' => 'required|string',
    ]);

    $task->update([
      'project' => $validated['project'],
      'name' => $validated['name'],
    ]);

    return redirect()->route('home.index');
  }


  public function destroy(Task $task)
  {
    $task->delete();

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
    $task = Task::find($id);

    if (!$task) {
      return response()->json(['error' => 'Task not found.'], 404);
    }

    $task->is_completed = !$task->is_completed;
    $task->save();

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
