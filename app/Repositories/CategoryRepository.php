<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository
{
    /**
     * Get all categories with optional filtering and pagination.
     *
     * @param bool $featured
     * @param string $sortBy
     * @param string $direction
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllCategories(bool $featured = false, string $sortBy = 'name', string $direction = 'asc', int $perPage = 15): LengthAwarePaginator
    {
        $query = Category::query();

        if ($featured) {
            $query->where('is_featured', true);
        }

        return $query->orderBy($sortBy, $direction)->paginate($perPage);
    }

    /**
     * Get featured categories.
     *
     * @param int $limit
     * @return Collection
     */
    public function getFeaturedCategories(int $limit = 4): Collection
    {
        return Category::where('is_featured', true)
            ->orderBy('name', 'asc')
            ->take($limit)
            ->get();
    }

    /**
     * Get a category by its slug.
     *
     * @param string $slug
     * @return Category
     */
    public function getCategoryBySlug(string $slug): Category
    {
        return Category::where('slug', $slug)->firstOrFail();
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Update an existing category.
     *
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function updateCategory(Category $category, array $data): Category
    {
        $category->update($data);
        return $category->fresh();
    }

    /**
     * Delete a category.
     *
     * @param Category $category
     * @return bool
     */
    public function deleteCategory(Category $category): bool
    {
        return $category->delete();
    }
}