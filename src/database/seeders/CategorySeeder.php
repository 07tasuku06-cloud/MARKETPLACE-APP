<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([

        ['name' => '服'],
        ['name' => '家電'],
        ['name' => '食品'],
        ['name' => '雑貨'],
        ['name' => '時計']

        ]);
    }
}
