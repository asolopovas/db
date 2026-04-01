<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'new_build')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->boolean('new_build')->default(false)->after('reverse_charge');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'new_build')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('new_build');
            });
        }
    }
};
