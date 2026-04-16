<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    // Hier den Namen korrigieren (wahrscheinlich das 's' am Ende entfernen)
    Schema::table('zeiteintraege', function (Blueprint $table) {
        $table->string('typ')->default('begleitung')->after('user_id');
        
        // Wichtig: 'change()' benötigt das Paket 'doctrine/dbal'
        // Falls du es nicht hast, lass das change() erstmal weg oder installiere es
        $table->unsignedBigInteger('schueler_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('zeiteintraege', function (Blueprint $table) {
        $table->dropColumn('typ');
        $table->unsignedBigInteger('schueler_id')->nullable(false)->change();
    });
}
};
