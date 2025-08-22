<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_materials', function (Blueprint $table) {
            $table->dropForeign(['material_id']);
        });
    }

    public function down(): void
    {
        Schema::table('order_materials', function (Blueprint $table) {
            $table->foreign('material_id')->references('id')->on('materials');
        });
    }
};
