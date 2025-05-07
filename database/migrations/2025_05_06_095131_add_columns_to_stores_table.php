<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('image')->nullable()->after('services');
            $table->string('email')->nullable()->after('image');
            $table->year('annee_ouverture')->nullable()->after('email');
            $table->string('site_web')->nullable()->after('annee_ouverture');
        });
    }

    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['image', 'email', 'annee_ouverture', 'site_web']);
        });
    }
};

