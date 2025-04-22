<?php

namespace App\Repositories;

use App\Models\Episode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EpisodeRepository
{
    /**
     * Get episodes for a podcast with optional filtering and pagination.
     *
     * @param int $podcastId
     * @param bool $featured
     * @param string $sortBy
     * @param string $direction
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPodcastEpisodes(int $podcastId, bool $featured = false, string $sortBy = 'published_at', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
    {
        $query = Episode::with('podcast')
            ->where('podcast_id', $podcastId);

        if ($featured) {
            $query->where('is_featured', true);
        }

        return $query->orderBy($sortBy, $direction)->paginate($perPage);
    }

    /**
     * Get recent episodes.
     *
     * @param int $limit
     * @return Collection
     */
    public function getRecentEpisodes(int $limit = 6): Collection
    {
        return Episode::with('podcast')
            ->orderBy('published_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get featured episodes.
     *
     * @param int $limit
     * @return Collection
     */
    public function getFeaturedEpisodes(int $limit = 6): Collection
    {
        return Episode::with('podcast')
            ->where('is_featured', true)
            ->orderBy('published_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get an episode by its slug and podcast ID.
     *
     * @param int $podcastId
     * @param string $episodeSlug
     * @return Episode
     */
    public function getEpisodeBySlug(int $podcastId, string $episodeSlug): Episode
    {
        return Episode::with('podcast')
            ->where('podcast_id', $podcastId)
            ->where('slug', $episodeSlug)
            ->firstOrFail();
    }

    /**
     * Create a new episode.
     *
     * @param array $data
     * @return Episode
     */
    public function createEpisode(array $data): Episode
    {
        return Episode::create($data);
    }

    /**
     * Update an existing episode.
     *
     * @param Episode $episode
     * @param array $data
     * @return Episode
     */
    public function updateEpisode(Episode $episode, array $data): Episode
    {
        $episode->update($data);
        return $episode->fresh();
    }

    /**
     * Delete an episode.
     *
     * @param Episode $episode
     * @return bool
     */
    public function deleteEpisode(Episode $episode): bool
    {
        return $episode->delete();
    }
}