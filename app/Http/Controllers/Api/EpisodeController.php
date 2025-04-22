<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EpisodeRequest;
use App\Http\Resources\EpisodeResource;
use App\Models\Episode;
use App\Models\Podcast;
use App\Services\EpisodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EpisodeController extends Controller
{
    protected $episodeService;

    public function __construct(EpisodeService $episodeService)
    {
        $this->episodeService = $episodeService;
    }

    /**
     * Display a listing of the episodes for a podcast.
     *
     * @param Request $request
     * @param string $podcastSlug
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, string $podcastSlug): AnonymousResourceCollection
    {
        $episodes = $this->episodeService->getPodcastEpisodes(
            $podcastSlug,
            $request->query('featured', false),
            $request->query('sort', 'published_at'),
            $request->query('direction', 'desc'),
            $request->query('per_page', 15)
        );

        return EpisodeResource::collection($episodes);
    }

    /**
     * Store a newly created episode in storage.
     *
     * @param EpisodeRequest $request
     * @param Podcast $podcast
     * @return JsonResponse
     */
    public function store(EpisodeRequest $request, Podcast $podcast): JsonResponse
    {
        $episode = $this->episodeService->createEpisode($podcast, $request->validated());

        return response()->json([
            'message' => 'Episode created successfully',
            'episode' => new EpisodeResource($episode),
        ], 201);
    }

    /**
     * Display the specified episode.
     *
     * @param string $podcastSlug
     * @param string $episodeSlug
     * @return EpisodeResource
     */
    public function show(string $podcastSlug, string $episodeSlug): EpisodeResource
    {
        $episode = $this->episodeService->getEpisodeBySlug($podcastSlug, $episodeSlug);

        return new EpisodeResource($episode);
    }

    /**
     * Update the specified episode in storage.
     *
     * @param EpisodeRequest $request
     * @param Episode $episode
     * @return JsonResponse
     */
    public function update(EpisodeRequest $request, Episode $episode): JsonResponse
    {
        $episode = $this->episodeService->updateEpisode($episode, $request->validated());

        return response()->json([
            'message' => 'Episode updated successfully',
            'episode' => new EpisodeResource($episode),
        ]);
    }

    /**
     * Remove the specified episode from storage.
     *
     * @param Episode $episode
     * @return JsonResponse
     */
    public function destroy(Episode $episode): JsonResponse
    {
        $this->episodeService->deleteEpisode($episode);

        return response()->json([
            'message' => 'Episode deleted successfully',
        ]);
    }

    /**
     * Get featured episodes across all podcasts.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function featured(Request $request): AnonymousResourceCollection
    {
        $episodes = $this->episodeService->getFeaturedEpisodes(
            $request->query('per_page', 6)
        );

        return EpisodeResource::collection($episodes);
    }
}