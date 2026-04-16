<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            // Wir prüfen, ob 'user_id' existiert. Wenn ja, benennen wir sie um.
            if (Schema::hasColumn('positions', 'user_id')) {
                $table->renameColumn('user_id', 'admin_id');
            } 
            // Falls weder 'user_id' noch 'admin_id' existieren, legen wir sie neu an.
            elseif (!Schema::hasColumn('positions', 'admin_id')) {
                $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            if (Schema::hasColumn('positions', 'admin_id')) {
                $table->renameColumn('admin_id', 'user_id');
            }
        });
    }
};