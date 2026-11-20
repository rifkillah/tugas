<?php

namespace Database\Factories;

use App\Models\Anggota;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Anggota>
 */
class AnggotaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nomor_anggota' => 'AGT-'.fake()->unique()->numerify('########'),
            'nama' => fake()->name(),
            'program_studi' => fake()->randomElement([
                'Teknik Informatika',
                'Sistem Informasi',
                'Manajemen',
                'Akuntansi',
                'Ilmu Komunikasi',
                'Pendidikan Bahasa Indonesia',
            ]),
            'angkatan' => (string) fake()->numberBetween(2020, 2025),
            'telepon' => '08'.fake()->numerify('##########'),
            'status' => 'aktif',
        ];
    }

    public function nonaktif(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'nonaktif',
        ]);
    }
}
