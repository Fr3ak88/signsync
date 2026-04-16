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
    Schema::create('monats_abschluesse', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->integer('monat');
        $table->integer('jahr');
        $table->timestamp('abgeschlossen_am');
        $table->timestamps();
        
        // Verhindert doppelte Einträge für denselben Monat/Nutzer
        $table->unique(['user_id', 'monat', 'jahr']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monats_abschluesse');
    }
};
