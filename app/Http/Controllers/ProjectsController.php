<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectsController extends CrudController
{
    protected string $model = Project::class;
    protected string $resource = "projects";
    protected array $messages = [];

    /**
     * @return array
     */
    protected function updateValidationRules()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function storeValidationRules()
    {
        return [];
    }
}
