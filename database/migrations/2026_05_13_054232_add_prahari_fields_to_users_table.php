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
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('phone')->nullable()->unique()->after('email');
            $table->string('otp')->nullable()->after('phone');
            $table->timestamp('otp_expire_at')->nullable()->after('otp');
            $table->boolean('is_active')->default(true)->after('otp_expire_at');
            // $table->enum('role', ['admin', 'user', 'prahari'])->default('prahari')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
            $table->dropColumn(['phone', 'otp', 'otp_expire_at', 'is_active']);
            // $table->enum('role', ['admin', 'user'])->default('user')->change();
        });
    }
};
