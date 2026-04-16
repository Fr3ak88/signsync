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
    Schema::table('schuelers', function (Blueprint $table) {
        $table->date('birth_date')->nullable()->after('name');
        $table->string('school_name')->nullable()->after('birth_date');
    });
}

public function down(): void
{
    Schema::table('schuelers', function (Blueprint $table) {
        $table->dropColumn(['birth_date', 'school_name']);
    });
}
};
