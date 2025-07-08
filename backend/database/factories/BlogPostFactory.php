<?php

namespace Database\Factories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(6);
        return [
            'title' => $title,
            'slug'  => Str::slug($title) . '-' . Str::random(5),
            'excerpt' => $this->faker->paragraph(2),
            'body' => '<p>' . implode('</p><p>', $this->faker->paragraphs(5)) . '</p>',
            'image' => null,
            'read_time' => $this->faker->numberBetween(3, 10),
            'published_at' => now(),
            'rating' => 0,
            'views' => 0,
        ];
    }
}