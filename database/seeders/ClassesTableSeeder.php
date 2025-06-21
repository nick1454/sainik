<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Classes;

class ClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classes::insert([
            ['name' => 'ONE', 'value' => 1],
            ['name' => 'TWO', 'value' => 2],
            ['name' => 'THREE', 'value' => 3],
            ['name' => 'FOUR', 'value' => 4],
            ['name' => 'FIVE', 'value' => 5],
            ['name' => 'SIX', 'value' => 6],
            ['name' => 'SEVEN', 'value' => 7],
            ['name' => 'EIGHT', 'value' => 8],
            ['name' => 'NINE', 'value' => 9],
            ['name' => 'TEN', 'value' => 10],
            ['name' => 'ELEVEN', 'value' => 11],
            ['name' => 'TWELEVE', 'value' => 12] 
        ]);
    }
}
