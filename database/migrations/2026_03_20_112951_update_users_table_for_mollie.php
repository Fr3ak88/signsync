<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Odoo Spalten entfernen
            $table->dropColumn(['odoo_partner_id', 'odoo_subscription_id']);
            
            // Mollie Spalte hinzufügen (nach dem Plan-Namen für die Ordnung)
            $table->string('mollie_customer_id')->nullable()->after('plan_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Falls wir zurückrollen, Odoo wieder rein (optional)
            $table->string('odoo_partner_id')->nullable();
            $table->string('odoo_subscription_id')->nullable();
            
            // Mollie raus
            $table->dropColumn('mollie_customer_id');
        });
    }
};