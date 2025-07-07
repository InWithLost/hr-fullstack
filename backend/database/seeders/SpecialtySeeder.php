<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            'cardiologist',
            'nurse',
            'pediatrician',
            'dentist',
            'surgeon',
        ];

        foreach ($specialties as $name) {
            Specialty::firstOrCreate([
                'slug' => $name,
            ], [
                'name' => ucfirst($name),
            ]);
        }
    }
}