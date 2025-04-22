<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PodcastResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseArray = [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'author' => $this->author,
            'image_url' => $this->image_url,
            'website_url' => $this->website_url,
            'is_featured' => $this->is_featured,
            'total_episodes' => $this->total_episodes,
            'last_published_at' => $this->last_published_at,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Add episodes when viewing a single podcast
        if ($request->routeIs('podcasts.show')) {
            $baseArray['recent_episodes'] = EpisodeResource::collection(
                $this->episodes()
                    ->orderBy('published_at', 'desc')
                    ->take(5)
                    ->get()
            );
        }

        return $baseArray;
    }
}