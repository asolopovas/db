<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Respect\Validation\Validator as v;

class CustomValidatorsProvider extends ServiceProvider
{
  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
    Validator::extend('phone', function(
      $attribute,
      $value,
      $parameters,
      $validator
    ) {

      $v = v::length(18, 18);

      return $v->validate($value);
    });
  }

  /**
   * Register the application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
