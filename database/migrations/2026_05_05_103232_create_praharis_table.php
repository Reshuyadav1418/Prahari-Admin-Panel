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
        Schema::create('praharis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('aadhar_number')->unique();
            $table->string('phone')->unique();
            $table->string('bank_account_number')->unique();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('praharis');
    }
};
