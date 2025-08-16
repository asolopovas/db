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
            $table->string('address_line_2')->nullable();
        });
    }

    public function down(): void
    {
    }
};
