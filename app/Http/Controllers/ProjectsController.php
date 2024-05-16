<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectsController extends Controller
{

    public function insert(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'color' => 'required|string|max:50',
        ]);

        // Create a new project
        $time = time();
        $project = Project::createProject([
            'name' => $request->name,
            'color' => $request->color,
            'created_at' => $time,
            'updated_at' => $time
        ]);

        // Redirect to the home index page after creating a new project
        return redirect()->route('home.index');
    }


    public function update(Request $request, $id)
    {
        // Find the relevant project instance
        $project = Project::find($id);

        if (!$project) {
            // If project not found, abort with 404 error
            abort(404, 'Project not found.');
        }

        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'color' => 'required|string|max:50',
        ]);

        // Update the project properties
        $project->updateProject([
            'name' => $request->name,
            'color' => $request->color,
            'updated_at' => time()
        ]);

        // Redirect or show a message indicating successful update
        return redirect()->route('home.index');
    }


    public function delete($id)
    {
        // Find the relevant project instance
        $project = Project::find($id);

        if (!$project) {
            // If project not found, abort with 404 error
            abort(404, 'Project not found.');
        }

        // Soft delete the project
        $project->delete();

        // In this part, all tasks dependent on the project should also be soft-deleted.

        // Redirect or show a message indicating successful deletion
        return redirect()->route('home.index');
    }
}
