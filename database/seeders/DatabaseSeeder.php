<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $tagNames = ['Electronics', 'Clothing', 'Books', 'Home', 'Sports', 'Toys', 'Food', 'Beauty'];
        $tags = collect();
        foreach ($tagNames as $name) {
            $tags->push(Tag::create(['tag_name' => $name]));
        }

        Product::factory()->count(15)->create()->each(function ($product) use ($tags) {
            $randomTags = $tags->random(rand(1, 3));
            $product->tags()->attach($randomTags->pluck('id'));

            $images = [];
            for ($i = 0; $i < rand(1, 3); $i++) {
                $images[] = 'images/placeholder_' . $product->id . '_' . $i . '.jpg';
            }
            $product->update(['images' => $images]);
        });
    }
}