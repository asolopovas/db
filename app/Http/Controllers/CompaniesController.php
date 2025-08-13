<?php

namespace App\Http\Controllers;

use App\Models\Company;

class CompaniesController extends CrudController
{

  protected string $model = Company::class;
  protected string $resource = "Company";
  protected array $messages = [];
  protected $rules = [];

  /**
   * @return array
   */
  protected function storeValidationRules()
  {
    return $this->rules;
  }

  /**
   * @return array
   */
  protected function updateValidationRules()
  {
    return $this->rules;
  }
}
