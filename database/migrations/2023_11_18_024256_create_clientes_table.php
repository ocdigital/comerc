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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
             $table->string('nome');
            $table->string('email')->unique();
            $table->string('telefone');
            $table->date('data_nascimento');
            $table->string('endereco');
            $table->string('complemento')->nullable();
            $table->string('bairro');
            $table->string('cep');
            $table->date('data_cadastro'); // talvez não seja necessário pois temos o created_at
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
