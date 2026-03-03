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
        $table->string('street')->nullable();
        $table->string('house_number')->nullable();
        $table->string('zip_code')->nullable();
        $table->string('city')->nullable();
        $table->string('country')->default('Deutschland');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['street', 'house_number', 'zip_code', 'city', 'country']);
    });
    }
};
