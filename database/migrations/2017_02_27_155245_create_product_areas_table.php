<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('area_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->decimal('meterage', 7, 2)->unsigned();
            $table->timestamps();
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');

            $table->foreign('area_id')
                  ->references('id')
                  ->on('areas')
                  ->onDelete('restrict');
            $table->unique(['area_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_areas');
    }
}
