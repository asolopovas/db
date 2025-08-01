<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraColumnsToOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('orders', function(Blueprint $table) {
      $table->decimal('total', 10, 2)->nullable()->after('payment_date');
      $table->decimal('vat_total', 10, 2)->nullable()->after('total');
      $table->decimal('balance', 10, 2)->after('grand_total')->nullable();
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
      $table->dropColumn('total');
      $table->dropColumn('vat_total');
      $table->dropColumn('balance');
    });
  }
}
