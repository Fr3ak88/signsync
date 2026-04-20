<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'avv_accepted_at')) {
            $table->timestamp('avv_accepted_at')->nullable();
        }
        if (!Schema::hasColumn('users', 'avv_accepted_ip')) {
            $table->string('avv_accepted_ip')->nullable();
        }
    });

}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['avv_accepted_at', 'avv_accepted_ip']);
    });
}
};
