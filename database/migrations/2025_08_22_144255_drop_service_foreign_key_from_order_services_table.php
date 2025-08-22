<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_services', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
        });
    }

    public function down(): void
    {
        Schema::table('order_services', function (Blueprint $table) {
            $table->foreign('service_id')->references('id')->on('services');
        });
    }
};