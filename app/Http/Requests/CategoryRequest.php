<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url'],
            'is_featured' => ['nullable', 'boolean'],
        ];

        // Add different validation for slug based on whether we're creating or updating
        if ($this->isMethod('post')) {
            // Creating a new category
            $rules['slug'] = ['nullable', 'string', 'max:255', 'unique:categories,slug'];
        } else {
            // Updating existing category
            $rules['slug'] = [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($this->route('category')),
            ];
        }

        return $rules;
    }
}