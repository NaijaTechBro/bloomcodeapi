<?php

namespace App\Services;

use App\Models\Episode;
use App\Models\Podcast;
use App\Repositories\EpisodeRepository;
use App\Repositories\PodcastRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class EpisodeService
{
    protected $episodeRepository;
    protected $podcastRepository;

    public function __construct(
        EpisodeRepository $episodeRepository,
        PodcastRepository $podcastRepository
    ) {
        $this->episodeRepository = $episodeRepository;
        $this->podcastRepository = $podcastRepository;
    }

    /**
     * Get episodes for a podcast with optional filtering and pagination.
     *
     * @param string $podcastSlug
     * @param bool $featured
     * @param string $sortBy
     * @param string $direction
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPodcastEpisodes(string $podcastSlug, bool $featured = false, string $sortBy = 'published_at', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
    {
        $podcast = $this->podcastRepository->getPodcastBySlug($podcastSlug);
        return $this->episodeRepository->getPodcastEpisodes($podcast->id, $featured, $sortBy, $direction, $perPage);
    }

    /**
     * Get recent episodes.
     *
     * @param int $limit
     * @return Collection
     */
    public function getRecentEpisodes(int $limit = 6): Collection
    {
        return $this->episodeRepository->getRecentEpisodes($limit);
    }

    /**
     * Get featured episodes.
     *
     * @param int $limit
     * @return Collection
     */
    public function getFeaturedEpisodes(int $limit = 6): Collection
    {
        return $this->episodeRepository->getFeaturedEpisodes($limit);
    }

    /**
     * Get an episode by its slug and podcast slug.
     *
     * @param string $podcastSlug
     * @param string $episodeSlug
     * @return Episode
     */
    public function getEpisodeBySlug(string $podcastSlug, string $episodeSlug): Episode
    {
        $podcast = $this->podcastRepository->getPodcastBySlug($podcastSlug);
        return $this->episodeRepository->getEpisodeBySlug($podcast->id, $episodeSlug);
    }

    /**
     * Create a new episode.
     *
     * @param Podcast $podcast
     * @param array $data
     * @return Episode
     */
    public function createEpisode(Podcast $podcast, array $data): Episode
    {
        if (!isset($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $data['podcast_id'] = $podcast->id;

        $episode = $this->episodeRepository->createEpisode($data);

        // Update podcast's total episodes count and last published date
        $this->updatePodcastEpisodeStats($podcast);

        return $episode;
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
        if (isset($data['title']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $episode = $this->episodeRepository->updateEpisode($episode, $data);

        // Update podcast's total episodes count and last published date
        $this->updatePodcastEpisodeStats($episode->podcast);

        return $episode;
    }

    /**
     * Delete an episode.
     *
     * @param Episode $episode
     * @return bool
     */
    public function deleteEpisode(Episode $episode): bool
    {
        $podcast = $episode->podcast;
        $deleted = $this->episodeRepository->deleteEpisode($episode);

        // Update podcast's total episodes count and last published date
        if ($deleted) {
            $this->updatePodcastEpisodeStats($podcast);
        }

        return $deleted;
    }

    /**
     * Update podcast's episode statistics.
     *
     * @param Podcast $podcast
     * @return void
     */
    private function updatePodcastEpisodeStats(Podcast $podcast): void
    {
        $totalEpisodes = $podcast->episodes()->count();
        $lastPublishedAt = $podcast->episodes()->max('published_at');

        $podcast->update([
            'total_episodes' => $totalEpisodes,
            'last_published_at' => $lastPublishedAt,
        ]);
    }
}