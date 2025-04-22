<?php
namespace Tests\Feature;

use App\Models\Episode;
use App\Models\Podcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EpisodeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_episodes()
    {
        Episode::factory()->count(5)->create();

        $response = $this->getJson('/api/episodes');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description', 'audio_url', 'duration', 'created_at', 'updated_at']
                ],
                'links',
                'meta'
            ]);
    }

    /** @test */
    public function it_can_show_an_episode()
    {
        $episode = Episode::factory()->create();

        $response = $this->getJson("/api/episodes/{$episode->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $episode->id,
                    'title' => $episode->title,
                    'description' => $episode->description,
                    'audio_url' => $episode->audio_url,
                ]
            ]);
    }

    /** @test */
    public function it_can_create_an_episode()
    {
        $podcast = Podcast::factory()->create();
        
        $episodeData = [
            'title' => 'New Episode',
            'description' => 'A new episode description',
            'audio_url' => 'https://example.com/audio.mp3',
            'duration' => 1800,
            'published_at' => now()->toDateTimeString(),
        ];

        $response = $this->postJson("/api/podcasts/{$podcast->id}/episodes", $episodeData);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'New Episode',
                    'description' => 'A new episode description',
                ]
            ]);
            
        $this->assertDatabaseHas('episodes', [
            'title' => 'New Episode',
            'podcast_id' => $podcast->id
        ]);
    }

    /** @test */
    public function it_can_update_an_episode()
    {
        $episode = Episode::factory()->create();
        
        $updatedData = [
            'title' => 'Updated Episode Title',
            'description' => 'Updated episode description',
        ];

        $response = $this->putJson("/api/episodes/{$episode->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'Updated Episode Title',
                    'description' => 'Updated episode description',
                ]
            ]);
            
        $this->assertDatabaseHas('episodes', [
            'id' => $episode->id,
            'title' => 'Updated Episode Title',
        ]);
    }

    /** @test */
    public function it_can_delete_an_episode()
    {
        $episode = Episode::factory()->create();

        $response = $this->deleteJson("/api/episodes/{$episode->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('episodes', [
            'id' => $episode->id
        ]);
    }

    /** @test */
    public function it_can_filter_episodes_by_podcast()
    {
        $podcast = Podcast::factory()->create();
        
        // Create 3 episodes for this podcast
        Episode::factory()->count(3)->create([
            'podcast_id' => $podcast->id
        ]);
        
        // Create 2 episodes for other podcasts
        Episode::factory()->count(2)->create();

        $response = $this->getJson("/api/episodes?podcast_id={$podcast->id}");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
}