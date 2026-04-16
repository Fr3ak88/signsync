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
    Schema::table('monatsabschlusse', function (Blueprint $table) {
        $table->longText('employee_signatur')->nullable()->after('schule_signatur');
    });
}

public function down()
{
    Schema::table('monatsabschlusse', function (Blueprint $table) {
        $table->dropColumn('employee_signatur');
    });
}
};
