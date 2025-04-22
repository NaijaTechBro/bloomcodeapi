<?php
namespace Database\Factories;

use App\Models\Category;
use App\Models\Podcast;
use Illuminate\Database\Eloquent\Factories\Factory;

class PodcastFactory extends Factory
{
    protected $model = Podcast::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(4),
            'category_id' => Category::factory(),
            'description' => $this->faker->paragraph(3),
            'image' => $this->faker->imageUrl(640, 480, 'podcast'),
            'author' => $this->faker->name(),
            'is_featured' => $this->faker->boolean(20), // 20% chance to be featured
        ];
    }

    public function featured()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_featured' => true,
            ];
        });
    }
}