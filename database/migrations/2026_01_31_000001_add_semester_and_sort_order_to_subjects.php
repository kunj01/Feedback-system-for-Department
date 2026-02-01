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
        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'semester')) {
                $table->integer('semester')->default(1)->after('code');
            }
            if (!Schema::hasColumn('subjects', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('semester');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['semester', 'sort_order']);
        });
    }
};
