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
        Schema::table('stores', function (Blueprint $table) {
            // On change la colonne "services" pour qu'elle soit de type JSON
            $table->json('services')->nullable()->change();
        });
    }
    
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            // Si tu veux revenir à du text
            $table->text('services')->nullable()->change();
        });
    }


};
