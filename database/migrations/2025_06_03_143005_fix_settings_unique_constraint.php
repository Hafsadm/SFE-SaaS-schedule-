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
        Schema::table('settings', function (Blueprint $table) {
            // Supprimer l'ancienne contrainte unique sur 'key' seulement
            $table->dropUnique(['key']);
            
            // Ajouter une nouvelle contrainte unique sur 'user_id' + 'key'
            $table->unique(['user_id', 'key'], 'settings_user_key_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Supprimer la contrainte composite
            $table->dropUnique('settings_user_key_unique');
            
            // Remettre l'ancienne contrainte sur 'key' seulement
            $table->unique('key', 'settings_key_unique');
        });
    }
};
