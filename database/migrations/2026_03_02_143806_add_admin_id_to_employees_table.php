<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('employees', function (Blueprint $table) {
        // Wir fügen admin_id hinzu, um den Mitarbeiter dem Admin zuzuordnen
        $table->foreignId('admin_id')->nullable()->after('user_id')->constrained('users')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('employees', function (Blueprint $table) {
        $table->dropForeign(['admin_id']);
        $table->dropColumn('admin_id');
    });
}
};
