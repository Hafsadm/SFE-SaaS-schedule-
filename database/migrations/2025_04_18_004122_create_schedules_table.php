<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->integer('day_of_week'); // 1 = Lundi, 7 = Dimanche
            $table->boolean('is_closed')->default(false);
            $table->boolean('is_holiday')->default(false);
            $table->date('exception_date')->nullable();
            $table->string('exception_raison')->nullable();
            $table->json('time_slots')->nullable(); // Format: [{"start": "09:00", "end": "18:00"}]
            $table->timestamps();

            // Index pour optimiser les recherches
            $table->index(['store_id', 'day_of_week']);
            $table->index('exception_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}; 