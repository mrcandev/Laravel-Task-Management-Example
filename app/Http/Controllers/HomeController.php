<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\TaskListService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
  public function index(
    Request $request,
    TaskListService $service
  ): View {
    $projectFilter = $request->input('projectFilter', 0);
    $statusFilter = $request->input('statusFilter', '');

    $tasks = $service->list($projectFilter, $statusFilter);

    return view('home.home')->with([
      'projects' => Project::all(),
      'tasks' => $tasks,
      'projectFilter' => $projectFilter,
      'statusFilter' => $statusFilter,
    ]);
  }
}
