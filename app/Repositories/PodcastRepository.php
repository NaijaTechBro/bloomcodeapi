<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Podcast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PodcastRepository
{
    /**
     * Get all podcasts with optional filtering and pagination.
     *
     * @param string|null $categorySlug
     * @param bool $featured
     * @param string|null $search
     * @param string $sortBy
     * @param string $direction
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPodcasts(?string $categorySlug = null, bool $featured = false, ?string $search = null, string $sortBy = 'title', string $direction = 'asc', int $perPage = 15): LengthAwarePaginator
    {
        $query = Podcast::with('category');

        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            $query->where('category_id', $category->id);
        }

        if ($featured) {
            $query->where('is_featured', true);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        return $query->orderBy($sortBy, $direction)->paginate($perPage);
    }

    /**
     * Get featured podcasts.
     *
     * @param int $limit
     * @return Collection
     */
    public function getFeaturedPodcasts(int $limit = 6): Collection
    {
        return Podcast::with('category')
            ->where('is_featured', true)
            ->orderBy('title', 'asc')
            ->take($limit)
            ->get();
    }

    /**
     * Get a podcast by its slug.
     *
     * @param string $slug
     * @return Podcast
     */
    public function getPodcastBySlug(string $slug): Podcast
    {
        return Podcast::with(['category', 'episodes' => function ($query) {
            $query->orderBy('published_at', 'desc');
        }])->where('slug', $slug)->firstOrFail();
    }

    /**
     * Create a new podcast.
     *
     * @param array $data
     * @return Podcast
     */
    public function createPodcast(array $data): Podcast
    {
        return Podcast::create($data);
    }

    /**
     * Update an existing podcast.
     *
     * @param Podcast $podcast
     * @param array $data
     * @return Podcast
     */
    public function updatePodcast(Podcast $podcast, array $data): Podcast
    {
        $podcast->update($data);
        return $podcast->fresh();
    }

    /**
     * Delete a podcast.
     *
     * @param Podcast $podcast
     * @return bool
     */
    public function deletePodcast(Podcast $podcast): bool
    {
        return $podcast->delete();
    }
}