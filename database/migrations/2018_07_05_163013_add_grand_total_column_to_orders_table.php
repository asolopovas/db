<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGrandTotalColumnToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('orders', function(Blueprint $table) {
        $table->decimal('grand_total', 10,2)->nullable()->after('payment_date');
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
        $table->dropColumn('grand_total');
      });
    }
}
