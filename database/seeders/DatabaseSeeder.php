<?php

namespace Database\Seeders;

use App\Models\Books\Books;
use App\Models\Books\Categories;
use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        Categories::create([
            'name' => 'Sejarah',
        ]);

        Categories::create([
            'name' => 'Fantasi',
        ]);

        Books::factory(10)->create();


        Roles::create([
            'name' => 'staff1',
        ]);
        Roles::create([
            'name' => 'staff2',
        ]);
    }
}
