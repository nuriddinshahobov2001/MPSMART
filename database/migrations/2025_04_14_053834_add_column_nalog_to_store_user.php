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
        Schema::table('store_user_models', function (Blueprint $table) {
            $table->string('client_id')->nullable()->after('store_id');
            $table->string('nalog_type')->nullable()->after('client_id');
            $table->string('nalog_percent')->nullable()->after('nalog_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_user_models', function (Blueprint $table) {
            $table->dropColumn('client_id');
            $table->dropColumn('nalog_type');
            $table->dropColumn('nalog_percent');
        });
    }
};
