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

  public function toggleCompletion(Request $request, Task $task)
  {
    $task->update([
      'is_completed' => $request->get('is_completed')
    ]);

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
