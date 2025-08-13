<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('customers', function(Blueprint $table) {
      $table->increments('id');
      $table->string('title')->nullable();
      $table->string('firstname')->nullable();
      $table->string('lastname')->nullable();
      $table->string('company')->nullable();
      $table->string('street')->nullable();
      $table->string('town')->nullable();
      $table->string('county')->nullable();
      $table->string('city')->nullable();
      $table->string('postcode')->nullable();
      $table->string('country')->nullable();
      $table->string('phone')->nullable();
      $table->string('mobile_phone')->nullable();
      $table->string('home_phone')->nullable();
      $table->string('work_phone')->nullable();
      $table->string('fax')->nullable();
      $table->string('email1');
      $table->string('email2')->nullable();
      $table->string('email3')->nullable();
      $table->string('note')->nullable();
      $table->string('web_page')->nullable();
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
    Schema::dropIfExists('customers');
  }
}
