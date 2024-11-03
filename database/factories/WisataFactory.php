<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wisata>
 */
class WisataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $nama_wisata = $this->faker->name();
        return [
            'nama_wisata' => $nama_wisata,
            'lokasi_maps' => $this->faker->url('https://www.google.com/maps/search/?api=1&query=' . urlencode($nama_wisata)),
            'link_foto'   => $this->faker->imageUrl(),
            'keterangan'  => $this->faker->sentence(),
            'fasilitas'   => $this->faker->sentence(),
            'biaya'       => $this->faker->randomFloat(2, 0, 100000),
            'situs'       => $this->faker->url(),
            'jenis_id'    => mt_rand(1, 13),
        ];
    }
}