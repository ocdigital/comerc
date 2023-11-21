<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nomes = [
            'Pastel de Carne',
            'Pastel de Frango com Catupiry',
            'Pastel de Queijo',
            'Pastel de Palmito',
            'Pastel de Camarão',
            'Pastel de Chocolate',
            'Pastel de Bacalhau',
            'Pastel de Calabresa',
            'Pastel de Milho',
            'Pastel de Banana',
            'Café Expresso',
            'Chá de Camomila',
            'Suco de Laranja',
            'Refrigerante de Cola',
            'Água Mineral',
            'Vinho Tinto',
            'Cerveja Artesanal',
            'Margarita',
            'Mojito',
            'Smoothie de Frutas',
            'Coxinha de Frango',
            'Empada de Palmito',
            'Quibe Frito',
            'Bolinha de Queijo',
            'Esfiha de Carne',
            'Croquete de Batata',
            'Enroladinho de Salsicha',
            'Pastel de Camarão',
            'Bolinho de Bacalhau',
            'Rissole de Milho'
        ];

        return [
            'nome' => $this->faker->randomElement($nomes),
            'preco' => $this->faker->randomFloat(2, 0, 9.99),
            'foto' => $this->faker->imageUrl(640, 480),
        ];
    }
}
