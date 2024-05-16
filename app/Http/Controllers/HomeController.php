<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        // Retrieve all projects and tasks
        $projects = Project::getAllProjects();
        $tasks = Task::getAllTasks();

        // Get filters from request
        $projectFilter = $request->input('projectFilter', 0);
        $statusFilter = $request->input('statusFilter', '');

        // Apply filters if provided
        if ($projectFilter != 0 || $statusFilter != '') {
            $tasksFilter = [];

            // Apply project filter if not set to all
            if ($projectFilter != 'all') {
                $tasksFilter['project'] = $projectFilter;
            }

            // Apply status filter if not set to all
            if ($statusFilter != 'all') {
                $tasksFilter['is_completed'] = ($statusFilter == 'completed') ? 1 : 0;
            }

            // Get tasks with applied filters
            $tasks = Task::getAllTasksWithFilters($tasksFilter);
        }

        // Return view with data
        return view('home.home')->with([
            'projects' => $projects,
            'tasks' => $tasks,
            'projectFilter' => $projectFilter,
            'statusFilter' => $statusFilter,
        ]);
    }
}
