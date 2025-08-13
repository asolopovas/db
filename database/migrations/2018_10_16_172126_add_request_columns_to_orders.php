<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequestColumnsToOrders extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('orders', function(Blueprint $table) {
      $table->timestamp('review_request')->nullable()->after('payment_date');
      $table->timestamp('photo_request')->nullable()->after('payment_date');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('orders', function(Blueprint $table) {
      $table->dropColumn('review_request');
      $table->dropColumn('photo_request');
    });
  }
}
