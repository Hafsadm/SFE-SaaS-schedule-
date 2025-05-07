
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('exterior_image')->nullable()->after('image');
            $table->string('interior_image')->nullable()->after('exterior_image');
            $table->string('equipment_image')->nullable()->after('interior_image');
        });
    }

    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['exterior_image', 'interior_image', 'equipment_image']);
        });
    }
};