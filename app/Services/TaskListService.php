<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Task;

class TaskListService
{
  public function __construct(private Task $model)
  {
    $this->model = $model;
  }

  public function list(int|string $projectFilter = 0, string $statusFilter = '')
  {
    if ($projectFilter != 0 || $statusFilter != '') {
      $tasksFilter = [];

      if ($projectFilter != 'all') {
        $tasksFilter['project'] = $projectFilter;
      }

      if ($statusFilter != 'all') {
        $tasksFilter['is_completed'] = ($statusFilter == 'completed') ? 1 : 0;
      }

      return $this->getAllTasksWithFilters($tasksFilter);
    }

    return $this->getAllTasks();
  }

  public function getAllTasks($search = null)
  {
    return $this->model->query()
      ->whereNull('tasks.deleted_at')
      ->join('projects', 'tasks.project', '=', 'projects.id')
      ->select('tasks.*', 'projects.name as project_name', 'projects.color as project_color')
      ->when($search, function ($query, $search) {
        $query->where('tasks.name', 'like', '%' . $search . '%');
      })
      ->orderBy('tasks.priority', 'ASC')
      ->get();
  }

  public function getAllTasksWithFilters(array $filters = [])
  {
    return $this->model->query()
      ->join('projects', 'tasks.project', '=', 'projects.id')
      ->select('tasks.*', 'projects.name as project_name', 'projects.color as project_color')
      ->whereNull('tasks.deleted_at')
      ->when(isset($filters['project']), function ($query) use ($filters) {
        $query->where('tasks.project', $filters['project']);
      })
      ->when(isset($filters['is_completed']), function ($query) use ($filters) {
        $query->where('tasks.is_completed', $filters['is_completed']);
      })
      ->orderBy('tasks.priority', 'ASC')
      ->get();
  }
}
