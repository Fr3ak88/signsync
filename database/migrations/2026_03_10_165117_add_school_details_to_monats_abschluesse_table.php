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
    Schema::table('monats_abschluesse', function (Blueprint $table) {
        $table->string('schule_unterzeichner')->nullable()->after('pdf_path');
        $table->longText('schule_signatur')->nullable()->after('schule_unterzeichner');
        $table->longText('employee_signatur')->nullable()->after('schule_signatur');
        $table->boolean('ist_abgeschlossen')->default(true)->after('employee_signatur');
    });
}

public function down(): void
{
    Schema::table('monats_abschluesse', function (Blueprint $table) {
        $table->dropColumn(['schule_unterzeichner', 'schule_signatur', 'employee_signatur', 'ist_abgeschlossen']);
    });
}
};
