<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('parent_store_id')->nullable()->constrained('stores');
            $table->boolean('is_main_store')->default(false);
        });
    }

    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['parent_store_id']);
            $table->dropColumn(['user_id', 'parent_store_id', 'is_main_store']);
        });
    }
};