<?php
namespace Database\Factories;

use App\Models\Episode;
use App\Models\Podcast;
use Illuminate\Database\Eloquent\Factories\Factory;

class EpisodeFactory extends Factory
{
    protected $model = Episode::class;

    public function definition()
    {
        return [
            'podcast_id' => Podcast::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(3),
            'audio_url' => 'https://example.com/audio/' . $this->faker->uuid() . '.mp3',
            'duration' => $this->faker->numberBetween(300, 7200), // 5 min to 2 hours
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}