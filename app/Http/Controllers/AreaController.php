<?php

namespace App\Http\Controllers;

use App\Models\Area;

class AreaController extends CrudController
{
  protected string $model = Area::class;
  protected string $resource = "area";
  protected array $messages = [];
  protected $rules = [
    "name" => "required|min:3|max:255|unique:areas,name,except,id"
  ];

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
