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
    Schema::create('frete_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // ✅ ID do usuário logado (se houver)
        $table->string('nome_cliente');
        $table->string('whatsapp_cliente');
        $table->string('origem');
        $table->string('destino');
        $table->string('tipo_caminhao');
        $table->boolean('precisa_ajudante')->default(false);
        $table->boolean('tem_escada')->default(false);
        $table->text('observacoes')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frete_requests');
    }
};
