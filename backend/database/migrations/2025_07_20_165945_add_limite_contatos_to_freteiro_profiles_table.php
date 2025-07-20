<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('freteiro_profiles', function (Blueprint $table) {
            $table->unsignedInteger('limite_contatos')->default(5)->after('whatsapp');
        });
    }

    public function down(): void {
        Schema::table('freteiro_profiles', function (Blueprint $table) {
            $table->dropColumn('limite_contatos');
        });
    }
};
