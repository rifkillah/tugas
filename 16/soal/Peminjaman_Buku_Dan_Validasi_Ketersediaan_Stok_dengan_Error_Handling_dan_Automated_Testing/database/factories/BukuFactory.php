<?php

namespace Database\Factories;

use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Buku>
 */
class BukuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $stokTotal = fake()->numberBetween(2, 8);

        return [
            'kode_buku' => 'BK-'.strtoupper(fake()->unique()->bothify('###??')),
            'judul' => fake()->sentence(3),
            'penulis' => fake()->name(),
            'penerbit' => fake()->company(),
            'tahun' => fake()->numberBetween(2000, 2025),
            'kategori' => fake()->randomElement([
                'Sastra', 'Teknologi', 'Sejarah', 'Metodologi', 'Manajemen', 'Psikologi',
            ]),
            'rak' => strtoupper(fake()->bothify('?-###')),
            'stok_total' => $stokTotal,
            'stok_tersedia' => $stokTotal,
            'sinopsis' => fake()->paragraph(3),
        ];
    }
}
