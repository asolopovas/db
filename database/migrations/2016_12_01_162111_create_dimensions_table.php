<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dimensions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('width');
            $table->integer('length');
            $table->integer('thickness');
            $table->string('type')->default('plank');
            $table->decimal('price', 5, 2);
            $table->timestamps();
            $table->unique(['width', 'length', 'thickness', 'type', 'price']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dimensions');
    }
}
