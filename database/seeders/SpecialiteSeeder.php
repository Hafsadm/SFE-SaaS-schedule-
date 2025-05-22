<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialite;

class SpecialiteSeeder extends Seeder
{
    public function run()
    {
        $specialites = [
            ['nom' => 'Opticien'],
            ['nom' => 'Audioprothésiste'],
            ['nom' => 'Orthoptiste'],
            ['nom' => 'Contactologue'],
            ['nom' => 'Lunetier'],
            ['nom' => 'Optométriste']
        ];

        // foreach ($specialites as $specialite) {
        //     Specialite::create($specialite);
        // }
    }
} 