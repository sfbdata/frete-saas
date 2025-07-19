<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('freteiro_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('nome_fantasia')->nullable();
            $table->string('nome_completo');
            $table->string('whatsapp')->nullable(); // ou required se for obrigatório
            $table->string('tipo_veiculo');
            $table->string('cidade_base');
            $table->text('descricao')->nullable();
            $table->string('foto_perfil')->nullable();
            $table->string('foto_caminhao')->nullable();
            $table->json('fotos_mudancas')->nullable();

            // ✅ Correções aqui:
            $table->decimal('avaliacao', 2, 1)->default(0); // Renomeado corretamente
            $table->unsignedInteger('quantidade_avaliacoes')->default(0); // Adicionado

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freteiro_profiles');
    }
};
