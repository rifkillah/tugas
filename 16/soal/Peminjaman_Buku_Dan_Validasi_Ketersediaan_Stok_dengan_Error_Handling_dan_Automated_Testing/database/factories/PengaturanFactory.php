<?php

namespace Database\Factories;

use App\Models\Pengaturan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pengaturan>
 */
class PengaturanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kunci' => fake()->unique()->slug(2),
            'nilai' => fake()->sentence(4),
            'keterangan' => fake()->sentence(6),
        ];
    }
}
