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
        // Unterscheidung zwischen Intern & Extern
        $table->boolean('is_internal')->default(false)->after('jahr');
        
        // Speicherpfad für das generierte PDF
        $table->string('pdf_path')->nullable()->after('is_internal');
    });
}

public function down(): void
{
    Schema::table('monats_abschluesse', function (Blueprint $table) {
        $table->dropColumn(['is_internal', 'pdf_path']);
    });
}
};
