<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectsController extends Controller
{
  public function store(Request $request, Project $project)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'color' => 'required|string|max:50',
    ]);

    $project->create([
      'name' => $validated['name'],
      'color' => $validated['color']
    ]);

    return redirect()->route('home.index');
  }

  public function update(Request $request, Project $project)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'color' => 'required|string|max:50',
    ]);

    $project->update([
      'name' => $validated['name'],
      'color' => $validated['color'],
    ]);

    return redirect()->route('home.index');
  }

  public function destroy(Project $project)
  {
    $project->delete();

    return redirect()->route('home.index');
  }
}
