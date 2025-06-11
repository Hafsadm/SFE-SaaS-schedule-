<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id(); // id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('code', 5);
            $table->string('name', 100);
            $table->string('native_name', 100);

            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);

            $table->timestamps(); // created_at & updated_at

            $table->index(['user_id', 'is_active'], 'idx_user_active');
            $table->unique(['user_id', 'code'], 'unique_user_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
