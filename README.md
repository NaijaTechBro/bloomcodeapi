# BloomCode API

A RESTful API built with Laravel for managing podcasts, episodes, and categories.

## Features

- RESTful API endpoints for podcasts, episodes, and categories
- Filtering, sorting, and pagination
- Comprehensive documentation with Swagger
- Dockerized environment
- Tests for all key endpoints

## Tech Stack

- PHP 8.2x
- Laravel 10+
- MySQL
- Docker
- Swagger (OpenAPI)

## Getting Started

### Prerequisites

- Docker and Docker Compose
- Git

### Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd bloomcodeapi

2. Copy the environment file:
   ```bash
   cp .env.example .env

3. Start the Docker containers:
    ```bash
   docker-compose up -d

4. Install dependencies:
   ```bash
   docker-compose exec app composer install 

5. Generate application key:
    ```bash
    docker-compose exec app php artisan key:generate

6. Run migrations and seeders:
   ```bash
   docker-compose exec app php artisan migrate --seed

7. Generate Swagger documentation:
   ```bash
   docker-compose exec app php artisan l5-swagger:generate

## API Documentation

 Once the application is running, you can access the API documentation at:

 - http://localhost:8000/api/documentation

### API Endpoints

Categories

- GET /api/categories - List all categories
- GET /api/categories/{category} - Get category details
- GET /api/categories/{category}/podcasts - Get podcasts by category

Podcasts

- GET /api/podcasts - List all podcasts
- GET /api/podcasts/featured - Get featured podcasts
- GET /api/podcasts/{podcast} - Get podcast details
- GET /api/podcasts/{podcast}/episodes - Get episodes by podcast
- POST /api/podcasts - Create a podcast
- PUT /api/podcasts/{podcast} - Update a podcast
- DELETE /api/podcasts/{podcast} - Delete a podcast

Episodes

- GET /api/episodes - List all episodes
- GET /api/episodes/{episode} - Get episode details
- POST /api/podcasts/{podcast}/episodes - Create an episode
- PUT /api/episodes/{episode} - Update an episode
- DELETE /api/episodes/{episode} - Delete an episode

### Query Parameters

Filtering

- category_id - Filter podcasts by category ID
- podcast_id - Filter episodes by podcast ID
- featured - Filter podcasts by featured status (true/false)
- search - Search in title and description

Sorting

- sort_by - Field to sort by (e.g., title, created_at)
- sort_direction - Direction to sort (asc or desc)

Pagination

- per_page - Number of items per page
- page - Page number

Running Tests

   ```bash
   docker-compose exec app php artisan test