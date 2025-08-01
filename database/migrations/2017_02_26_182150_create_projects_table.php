<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('projects', function(Blueprint $table) {
      $table->increments('id');
      $table->string('street')->nullable();
      $table->string('county')->nullable();
      $table->string('town')->nullable();
      $table->string('city')->nullable();
      $table->string('postcode')->nullable();
      $table->string('country')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('projects');
  }
}
