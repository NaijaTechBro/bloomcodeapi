<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PodcastRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'author' => ['required', 'string', 'max:255'],
            'image_url' => ['required', 'url'],
            'website_url' => ['nullable', 'url'],
            'is_featured' => ['nullable', 'boolean'],
            'last_published_at' => ['nullable', 'date'],
        ];

        // Add different validation for slug based on whether we're creating or updating
        if ($this->isMethod('post')) {
            // Creating a new podcast
            $rules['slug'] = ['nullable', 'string', 'max:255', 'unique:podcasts,slug'];
        } else {
            // Updating existing podcast
            $rules['slug'] = [
                'nullable',
                'string',
                'max:255',
                Rule::unique('podcasts', 'slug')->ignore($this->route('podcast')),
            ];
        }

        return $rules;
    }
}