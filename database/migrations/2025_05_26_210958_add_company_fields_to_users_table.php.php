<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Informations de l'entreprise
            $table->string('company_name')->nullable();
            $table->string('website_name')->nullable();
            $table->string('website_url')->nullable();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            
            // Paramètres d'apparence
            $table->string('primary_color')->default('#0A2E2E');
            $table->string('secondary_color')->default('#2A6363');
            $table->string('accent_color')->default('#8E6E53');
            $table->string('theme')->default('light');
            
            // Slug unique pour l'URL publique
            $table->string('admin_slug')->unique()->nullable();
            
            // Paramètres système
            $table->string('timezone')->default('Europe/Paris');
            $table->string('locale')->default('fr');
            $table->boolean('is_active')->default(true);
            
            // Timestamp pour savoir quand régénérer le CSS
            $table->timestamp('settings_updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'website_name', 
                'website_url',
                'logo',
                'description',
                'primary_color',
                'secondary_color',
                'accent_color',
                'theme',
                'admin_slug',
                'timezone',
                'locale',
                'is_active',
                'settings_updated_at'
            ]);
        });
    }
};
