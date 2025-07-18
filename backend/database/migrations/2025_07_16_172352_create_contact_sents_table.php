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
    Schema::create('contact_sents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('freteiro_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('frete_request_id')->constrained()->onDelete('cascade');
        $table->timestamp('sent_at')->useCurrent();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_sents');
    }
};
