<?php

namespace Database\Factories;

use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Buku>
 */
class BukuFactory extends Factory
{
    protected $model = Buku::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_buku' => 'BK-' . fake()->unique()->numerify('#####'),
            'judul_buku' => fake()->sentence(3),
            'penulis' => fake()->name(),
            'stok' => fake()->numberBetween(1, 50),
        ];
    }
}
