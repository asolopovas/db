<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderMaterialsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('order_materials', function(Blueprint $table) {
      $table->increments('id');
      $table->integer('order_id')->unsigned();
      $table->integer('material_id')->unsigned();
      $table->decimal('unit_price', 7, 2)->unsigned();
      $table->integer('quantity')->unsinged();
      $table->timestamps();

      $table->foreign('order_id')
            ->references('id')
            ->on('orders')
            ->onDelete('cascade');

      $table->foreign('material_id')
            ->references('id')
            ->on('materials')
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
    Schema::dropIfExists('order_materials');
  }
}
