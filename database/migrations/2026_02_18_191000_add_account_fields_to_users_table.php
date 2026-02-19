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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('profile_image', 500)->nullable()->after('phone');
            $table->boolean('must_change_password')->default(true)->after('is_active');
            $table->index('must_change_password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['must_change_password']);
            $table->dropColumn(['phone', 'profile_image', 'must_change_password']);
        });
    }
};
