<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('telephone1')->nullable();
            $table->string('telephone2')->nullable();
            $table->string('web')->nullable();
            $table->string('email')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('bank')->nullable();
            $table->string('sort_code')->nullable();
            $table->string('account_nr')->nullable();
            $table->string('iban')->nullable();
            $table->string('swift')->nullable();
            $table->longText('disclaimer')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
