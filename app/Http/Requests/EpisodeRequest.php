<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EpisodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'podcast_id' => ['required', 'exists:podcasts,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'audio_url' => ['required', 'url'],
            'duration_in_seconds' => ['required', 'integer', 'min:1'],
            'image_url' => ['nullable', 'url'],
            'episode_number' => ['required', 'integer', 'min:1'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ];
        
        // Add different validation for slug based on whether we're creating or updating
        if ($this->isMethod('post')) {
            // Creating a new episode
            $rules['slug'] = ['nullable', 'string', 'max:255', 'unique:episodes,slug'];
        } else {
            // Updating existing episode
            $rules['slug'] = [
                'nullable',
                'string',
                'max:255',
                Rule::unique('episodes', 'slug')->ignore($this->route('episode')),
            ];
        }
        
        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'podcast_id.exists' => 'The selected podcast does not exist.',
            'audio_url.url' => 'The audio URL must be a valid URL.',
            'image_url.url' => 'The image URL must be a valid URL.',
            'duration_in_seconds.min' => 'The episode duration must be at least 1 second.',
            'episode_number.min' => 'The episode number must be at least 1.',
            'slug.unique' => 'This episode slug has already been taken.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'podcast_id' => 'podcast',
            'duration_in_seconds' => 'duration',
            'published_at' => 'publication date',
        ];
    }
}