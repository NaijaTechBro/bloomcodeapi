<?php
namespace Tests\Feature;

use App\Models\Category;
use App\Models\Podcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_categories()
    {
        Category::factory()->count(5)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug', 'created_at', 'updated_at']
                ],
                'links',
                'meta'
            ]);
    }

    /** @test */
    public function it_can_show_a_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ]
            ]);
    }

    /** @test */
    public function it_can_list_podcasts_by_category()
    {
        $category = Category::factory()->create();
        $podcasts = Podcast::factory()->count(3)->create([
            'category_id' => $category->id
        ]);

        $response = $this->getJson("/api/categories/{$category->id}/podcasts");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description', 'image', 'created_at', 'updated_at']
                ],
                'links',
                'meta'
            ]);
    }

    /** @test */
    public function it_returns_404_for_non_existent_category()
    {
        $response = $this->getJson('/api/categories/999');
        $response->assertStatus(404);
    }
}