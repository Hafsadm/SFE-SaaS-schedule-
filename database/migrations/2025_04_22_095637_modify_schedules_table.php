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
        Schema::table('schedules', function (Blueprint $table) {
            // Supprimer les colonnes qui ne sont plus nécessaires
            $table->dropColumn(['is_holiday', 'exception_date', 'exception_raison']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Rétablir les colonnes supprimées
            $table->boolean('is_holiday')->default(false);
            $table->date('exception_date')->nullable();
            $table->string('exception_raison')->nullable();
        });
    }
};