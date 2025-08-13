<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('products', function(Blueprint $table) {
      // Fields
      $table->increments('id');
      $table->integer('order_id')->unsigned();
      $table->integer('floor_id')->unsigned();
      $table->integer('extra_id')->nullable()->unsigned();
      $table->integer('dimension_id')->unsigned();
      $table->integer('grade_id')->unsigned();
      $table->decimal('discount', 4, 2)->nullable()->unsigned();
      $table->decimal('wastage_rate', 4, 2)->nullable()->unsigned();
      $table->decimal('meterage', 6, 2)->nullable()->unsigned();
      $table->decimal('unit_price', 6, 2)->nullable()->unsigned();
      // Timestamps
      $table->timestamps();
      // Foreign Keys
      $table->foreign('order_id')
            ->references('id')
            ->on('orders')
            ->onDelete('cascade');
      $table->foreign('floor_id')
            ->references('id')
            ->on('floors')
            ->onDelete('restrict');
      $table->foreign('extra_id')
            ->references('id')
            ->on('extras')
            ->onDelete('restrict');
      $table->foreign('grade_id')
            ->references('id')
            ->on('grades')
            ->onDelete('restrict');
      $table->foreign('dimension_id')
            ->references('id')
            ->on('dimensions')
            ->onDelete('restrict');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('products');
  }
}
