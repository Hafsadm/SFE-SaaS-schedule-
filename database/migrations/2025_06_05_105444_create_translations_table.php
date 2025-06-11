<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id(); // id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('key');
            $table->text('value');
            $table->string('language', 5);
            $table->boolean('is_auto_translated')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps(); // created_at & updated_at

            $table->index(['user_id', 'language'], 'idx_user_language');
            $table->index(['key', 'language'], 'idx_key_language');
            $table->unique(['user_id', 'key', 'language'], 'unique_user_key_language');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
