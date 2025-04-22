<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\EpisodeResource;
use App\Http\Resources\PodcastResource;
use App\Services\CategoryService;
use App\Services\EpisodeService;
use App\Services\PodcastService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    protected $categoryService;
    protected $podcastService;
    protected $episodeService;

    public function __construct(
        CategoryService $categoryService,
        PodcastService $podcastService,
        EpisodeService $episodeService
    ) {
        $this->categoryService = $categoryService;
        $this->podcastService = $podcastService;
        $this->episodeService = $episodeService;
    }

    /**
     * Get content for the landing page.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $featuredCategories = $this->categoryService->getFeaturedCategories(4);
        $featuredPodcasts = $this->podcastService->getFeaturedPodcasts(6);
        $recentEpisodes = $this->episodeService->getRecentEpisodes(6);
        $featuredEpisodes = $this->episodeService->getFeaturedEpisodes(6);

        return response()->json([
            'featured_categories' => CategoryResource::collection($featuredCategories),
            'featured_podcasts' => PodcastResource::collection($featuredPodcasts),
            'recent_episodes' => EpisodeResource::collection($recentEpisodes),
            'featured_episodes' => EpisodeResource::collection($featuredEpisodes),
        ]);
    }
}