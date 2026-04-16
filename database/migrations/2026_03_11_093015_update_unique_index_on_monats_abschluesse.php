<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('monats_abschluesse', function (Blueprint $table) {
            // 1. Einen simplen Index für user_id erstellen. 
            // Das gibt MySQL eine Alternative für den Foreign Key.
            $table->index('user_id', 'temp_user_id_index');
        });

        Schema::table('monats_abschluesse', function (Blueprint $table) {
            // 2. Jetzt den alten Unique Index löschen. 
            // Da 'temp_user_id_index' existiert, sollte MySQL jetzt zustimmen.
            $table->dropUnique(['user_id', 'monat', 'jahr']);
            
            // 3. Den neuen erweiterten Unique Index anlegen.
            $table->unique(['user_id', 'monat', 'jahr', 'is_internal'], 'user_month_year_type_unique');
        });
        
        Schema::table('monats_abschluesse', function (Blueprint $table) {
            // 4. Den temporären Index wieder löschen (optional, der neue Unique reicht aus)
            $table->dropIndex('temp_user_id_index');
        });
    }

    public function down()
    {
        Schema::table('monats_abschluesse', function (Blueprint $table) {
            $table->dropUnique('user_month_year_type_unique');
            $table->unique(['user_id', 'monat', 'jahr']);
        });
    }
};