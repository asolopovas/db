<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('street', 'address_line_1');
            $table->dropColumn('grand_design');
            $table->enum('status', ['ACTIVE', 'ARCHIVED'])->default('ACTIVE');
            $table->string('address_line_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('address_line_1', 'street');
            $table->dropColumn('status');
            $table->dropColumn('address_line_2');
        });
    }
};
