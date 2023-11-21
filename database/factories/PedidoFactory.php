<?php

namespace Database\Factories;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition()
    {
        return [
            'codigo_cliente' => 1
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Pedido $pedido) {
            $produtos = [
                ['produto_id' => 1, 'quantidade' => 2],
                ['produto_id' => 2, 'quantidade' => 3],
            ];

            foreach ($produtos as $produto) {
                $pedido->produtos()->attach($produto['produto_id'], ['quantidade' => $produto['quantidade']]);
            }
        });
    }
}
