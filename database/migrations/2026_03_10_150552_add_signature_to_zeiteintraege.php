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
    Schema::table('zeiteintraege', function (Blueprint $table) {
        // Wir lassen das "after('notizen')" weg, dann wird sie einfach hinten angehängt
        $table->longText('signature')->nullable();
    });
}

public function down()
{
    Schema::table('zeiteintraege', function (Blueprint $table) {
        $table->dropColumn('signature');
    });
}
};
