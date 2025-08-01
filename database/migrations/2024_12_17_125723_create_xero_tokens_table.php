<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xero_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->unique()->nullable();
            $table->string('refresh_token', 2048)->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamp('reauthenticate_at')->nullable();
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
        Schema::dropIfExists('xero_tokens');
    }
};
