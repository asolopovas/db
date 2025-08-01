<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('orders', function(Blueprint $table) {
      $table->increments('id');
      $table->integer('customer_id')->unsigned();
      $table->integer('company_id')->unsigned();
      $table->integer('status_id')->unsigned();
      $table->integer('user_id')->unsigned();
      $table->integer('project_id')->unsigned()->nullable();
      $table->string('cc');
      $table->longText('mail_message');
      $table->longText('proforma_message');
      $table->longText('payment_terms');
      $table->longText('notes');
      $table->boolean('proforma');
      $table->timestamp('sent');
      $table->decimal('vat', 4, 2);
      $table->decimal('discount', 10, 2);
      $table->timestamp('date_due')->nullable();
      $table->timestamps();

      $table->foreign('customer_id')
            ->references('id')
            ->on('customers')
            ->onDelete('restrict');

      $table->foreign('company_id')
            ->references('id')
            ->on('companies')
            ->onDelete('restrict');

      $table->foreign('status_id')
            ->references('id')
            ->on('statuses')
            ->onDelete('restrict');

      $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('restrict');

      $table->foreign('project_id')
            ->references('id')
            ->on('projects')
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
    Schema::drop('orders');
  }
}
