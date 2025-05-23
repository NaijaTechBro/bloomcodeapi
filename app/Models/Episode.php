<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Episode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'podcast_id',
        'title',
        'slug',
        'description',
        'audio_url',
        'duration_in_seconds',
        'image_url',
        'episode_number',
        'is_featured',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the podcast that owns the episode.
     */
    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class);
    }

    /**
     * Get formatted duration
     *
     * @return string
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->duration_in_seconds / 3600);
        $minutes = floor(($this->duration_in_seconds % 3600) / 60);
        $seconds = $this->duration_in_seconds % 60;

        if ($hours > 0) {
            return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
        }

        return sprintf("%02d:%02d", $minutes, $seconds);
    }
}