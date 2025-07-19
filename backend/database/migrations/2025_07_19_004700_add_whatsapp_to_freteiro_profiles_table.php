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
            Schema::table('freteiro_profiles', function (Blueprint $table) {
                $table->string('whatsapp')->nullable()->after('cidade_base');
            });
        }

    public function down(): void
        {
            Schema::table('freteiro_profiles', function (Blueprint $table) {
                $table->dropColumn('whatsapp');
            });
        }
};
