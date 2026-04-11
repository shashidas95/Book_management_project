<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Library;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibrarySystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Library 1: Dhaka Central Library
        $lib1 = Library::create([
            'name' => 'Dhaka Central Library',
            'slug' => 'dhaka-central',
            'address' => 'Shahbag, Dhaka'
        ]);

        // Create Library 2: BSTI Reference Lab
        $lib2 = Library::create([
            'name' => 'BSTI Reference Library',
            'slug' => 'bsti-ref',
            'address' => 'Tejgaon, Dhaka'
        ]);

        // Seed 5 Categories for each library
        foreach ([$lib1, $lib2] as $library) {
            $categories = Category::factory(5)->create([
                'library_id' => $library->id
            ]);

            // Seed 20 Books for each library, distributed among its categories
            foreach ($categories as $category) {
                Book::factory(4)->create([
                    'library_id' => $library->id,
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
