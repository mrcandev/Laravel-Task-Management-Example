<?php

declare(strict_types=1);

namespace app\Services\Project;

use App\Models\Project;

class ProjectListService
{
  public function __construct(protected Project $model)
  {
    $this->model = $model;
  }
}
