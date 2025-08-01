<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_deductions', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('order_id')->unsigned();
          $table->string('ref');
          $table->decimal('amount', 8, 2);
          $table->date('date');
          $table->timestamps();

          $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_deductions');
    }
}
