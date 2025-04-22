<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

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
        return $this->categoryRepository->getAllCategories($featured, $sortBy, $direction, $perPage);
    }

    /**
     * Get featured categories.
     *
     * @param int $limit
     * @return Collection
     */
    public function getFeaturedCategories(int $limit = 4): Collection
    {
        return $this->categoryRepository->getFeaturedCategories($limit);
    }

    /**
     * Get a category by its slug.
     *
     * @param string $slug
     * @return Category
     */
    public function getCategoryBySlug(string $slug): Category
    {
        return $this->categoryRepository->getCategoryBySlug($slug);
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category
    {
        if (!isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $this->categoryRepository->createCategory($data);
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
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $this->categoryRepository->updateCategory($category, $data);
    }

    /**
     * Delete a category.
     *
     * @param Category $category
     * @return bool
     */
    public function deleteCategory(Category $category): bool
    {
        return $this->categoryRepository->deleteCategory($category);
    }
}