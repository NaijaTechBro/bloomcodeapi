<?php
namespace Tests\Feature;

use App\Models\Category;
use App\Models\Podcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PodcastTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_podcasts()
    {
        Podcast::factory()->count(5)->create();

        $response = $this->getJson('/api/podcasts');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description', 'image', 'created_at', 'updated_at']
                ],
                'links',
                'meta'
            ]);
    }

    /** @test */
    public function it_can_list_featured_podcasts()
    {
        // Create 3 featured podcasts
        Podcast::factory()->count(3)->create([
            'is_featured' => true
        ]);
        
        // Create 2 non-featured podcasts
        Podcast::factory()->count(2)->create([
            'is_featured' => false
        ]);

        $response = $this->getJson('/api/podcasts/featured');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_show_a_podcast()
    {
        $podcast = Podcast::factory()->create();

        $response = $this->getJson("/api/podcasts/{$podcast->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $podcast->id,
                    'title' => $podcast->title,
                    'description' => $podcast->description,
                ]
            ]);
    }

    /** @test */
    public function it_can_filter_podcasts_by_category()
    {
        $category = Category::factory()->create();
        
        // Create 3 podcasts in the category
        Podcast::factory()->count(3)->create([
            'category_id' => $category->id
        ]);
        
        // Create 2 podcasts in a different category
        Podcast::factory()->count(2)->create();

        $response = $this->getJson("/api/podcasts?category_id={$category->id}");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_create_a_podcast()
    {
        $category = Category::factory()->create();
        
        $podcastData = [
            'title' => 'New Podcast',
            'category_id' => $category->id,
            'description' => 'A new podcast description',
            'image' => 'https://example.com/image.jpg',
            'author' => 'John Doe',
            'is_featured' => false
        ];

        $response = $this->postJson('/api/podcasts', $podcastData);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'New Podcast',
                    'description' => 'A new podcast description',
                ]
            ]);
            
        $this->assertDatabaseHas('podcasts', [
            'title' => 'New Podcast',
            'category_id' => $category->id
        ]);
    }

    /** @test */
    public function it_can_update_a_podcast()
    {
        $podcast = Podcast::factory()->create();
        
        $updatedData = [
            'title' => 'Updated Podcast Title',
            'description' => 'Updated podcast description',
        ];

        $response = $this->putJson("/api/podcasts/{$podcast->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'Updated Podcast Title',
                    'description' => 'Updated podcast description',
                ]
            ]);
            
        $this->assertDatabaseHas('podcasts', [
            'id' => $podcast->id,
            'title' => 'Updated Podcast Title',
        ]);
    }

    /** @test */
    public function it_can_delete_a_podcast()
    {
        $podcast = Podcast::factory()->create();

        $response = $this->deleteJson("/api/podcasts/{$podcast->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('podcasts', [
            'id' => $podcast->id
        ]);
    }
}