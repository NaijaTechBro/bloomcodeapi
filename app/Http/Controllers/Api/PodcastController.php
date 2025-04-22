<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PodcastRequest;
use App\Http\Resources\PodcastResource;
use App\Models\Podcast;
use App\Services\PodcastService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PodcastController extends Controller
{
    protected $podcastService;

    public function __construct(PodcastService $podcastService)
    {
        $this->podcastService = $podcastService;
    }

    /**
     * Display a listing of the podcasts.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $podcasts = $this->podcastService->getAllPodcasts(
            $request->query('category'),
            $request->query('featured', false),
            $request->query('search'),
            $request->query('sort', 'title'),
            $request->query('direction', 'asc'),
            $request->query('per_page', 15)
        );

        return PodcastResource::collection($podcasts);
    }

    /**
     * Store a newly created podcast in storage.
     *
     * @param PodcastRequest $request
     * @return JsonResponse
     */
    public function store(PodcastRequest $request): JsonResponse
    {
        $podcast = $this->podcastService->createPodcast($request->validated());

        return response()->json([
            'message' => 'Podcast created successfully',
            'podcast' => new PodcastResource($podcast),
        ], 201);
    }

    /**
     * Display the specified podcast.
     *
     * @param string $slug
     * @return PodcastResource
     */
    public function show(string $slug): PodcastResource
    {
        $podcast = $this->podcastService->getPodcastBySlug($slug);

        return new PodcastResource($podcast);
    }

    /**
     * Update the specified podcast in storage.
     *
     * @param PodcastRequest $request
     * @param Podcast $podcast
     * @return JsonResponse
     */
    public function update(PodcastRequest $request, Podcast $podcast): JsonResponse
    {
        $podcast = $this->podcastService->updatePodcast($podcast, $request->validated());

        return response()->json([
            'message' => 'Podcast updated successfully',
            'podcast' => new PodcastResource($podcast),
        ]);
    }

    /**
     * Remove the specified podcast from storage.
     *
     * @param Podcast $podcast
     * @return JsonResponse
     */
    public function destroy(Podcast $podcast): JsonResponse
    {
        $this->podcastService->deletePodcast($podcast);

        return response()->json([
            'message' => 'Podcast deleted successfully',
        ]);
    }

    /**
     * Get featured podcasts for the landing page.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function featured(Request $request): AnonymousResourceCollection
    {
        $podcasts = $this->podcastService->getFeaturedPodcasts(
            $request->query('per_page', 6)
        );

        return PodcastResource::collection($podcasts);
    }
}