<?php

namespace App\Services;

use App\Models\Podcast;
use App\Repositories\PodcastRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class PodcastService
{
    protected $podcastRepository;

    public function __construct(PodcastRepository $podcastRepository)
    {
        $this->podcastRepository = $podcastRepository;
    }

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
        return $this->podcastRepository->getAllPodcasts($categorySlug, $featured, $search, $sortBy, $direction, $perPage);
    }

    /**
     * Get featured podcasts.
     *
     * @param int $limit
     * @return Collection
     */
    public function getFeaturedPodcasts(int $limit = 6): Collection
    {
        return $this->podcastRepository->getFeaturedPodcasts($limit);
    }

    /**
     * Get a podcast by its slug.
     *
     * @param string $slug
     * @return Podcast
     */
    public function getPodcastBySlug(string $slug): Podcast
    {
        return $this->podcastRepository->getPodcastBySlug($slug);
    }

    /**
     * Create a new podcast.
     *
     * @param array $data
     * @return Podcast
     */
    public function createPodcast(array $data): Podcast
    {
        if (!isset($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        return $this->podcastRepository->createPodcast($data);
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
        if (isset($data['title']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        return $this->podcastRepository->updatePodcast($podcast, $data);
    }

    /**
     * Delete a podcast.
     *
     * @param Podcast $podcast
     * @return bool
     */
    public function deletePodcast(Podcast $podcast): bool
    {
        return $this->podcastRepository->deletePodcast($podcast);
    }
}

