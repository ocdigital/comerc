<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->name(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber(),
            'data_nascimento' => $this->faker->date(),
            'endereco' => $this->faker->address(),
            'complemento' => $this->faker->secondaryAddress(),
            'bairro' => $this->faker->city(),
            'cep' => $this->faker->postcode(),
        ];
    }
}
