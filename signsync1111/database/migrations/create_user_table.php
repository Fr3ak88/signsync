<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            // Wir fügen die neuen Felder hinzu
            $blueprint->string('first_name')->after('id');
            $blueprint->string('last_name')->after('first_name');
            $blueprint->string('street')->nullable()->after('email');
            $blueprint->string('house_number')->nullable()->after('street');
            $blueprint->string('zip_code')->nullable()->after('house_number');
            $blueprint->string('city')->nullable()->after('zip_code');
            $blueprint->string('country')->default('Deutschland')->after('city');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            // Falls wir die Migration rückgängig machen, löschen wir die Spalten wieder
            $blueprint->dropColumn(['first_name', 'last_name', 'street', 'house_number', 'zip_code', 'city', 'country']);
        });
    }
};