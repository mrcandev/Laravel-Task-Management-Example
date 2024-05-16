<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     */
    protected $fillable = [
        'project', 'name', 'priority', 'is_completed'
    ];

    /**
     * Get all tasks.
     *
     */
    public static function getAllTasks($search = null)
    {
        $query = static::query();

        // If there's a search criteria, filter tasks based on it
        if ($search !== null) {
            $query->whereNull('tasks.deleted_at');
            $query->where('tasks.name', 'like', '%' . $search . '%');
            $query->join('projects', 'tasks.project', '=', 'projects.id');
            $query->select('tasks.*', 'projects.name as project_name', 'projects.color as project_color');
            $query->orderBy('tasks.priority', 'ASC'); // Order by priority
        } else {
            $query->whereNull('tasks.deleted_at');
            $query->join('projects', 'tasks.project', '=', 'projects.id');
            $query->select('tasks.*', 'projects.name as project_name', 'projects.color as project_color');
            $query->orderBy('tasks.priority', 'ASC'); // Order by priority
        }

        return $query->get();
    }

    /**
     * Get all tasks and optionally filter them.
     *
     */
    public static function getAllTasksWithFilters($filters = [])
    {
        $query = static::query();

        $query->join('projects', 'tasks.project', '=', 'projects.id')
            ->select('tasks.*', 'projects.name as project_name', 'projects.color as project_color')
            ->whereNull('tasks.deleted_at');

        if (isset($filters['project'])) {
            $query->where('tasks.project', $filters['project']);
        }

        if (isset($filters['is_completed'])) {
            $query->where('tasks.is_completed', $filters['is_completed']);
        }

        return $query->orderBy('tasks.priority', 'ASC')->get();
    }


    /**
     * Create a new task.
     *
     */
    public static function createTask(array $data)
    {
        return static::create($data);
    }

    /**
     * Update a task.
     *
     */
    public function updateTask(array $data)
    {
        return $this->update($data);
    }

    /**
     * Soft delete a task.
     *
     */
    public function deleteTask()
    {
        return $this->delete();
    }
}
