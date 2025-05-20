<?php

namespace App\Services;

use App\Models\Genre;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TmdbService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.themoviedb.org/3';
    protected int $cacheTtl = 60 * 60; // 1 hour

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');

        if (empty($this->apiKey)) {
            throw new \RuntimeException('TMDB API key is not configured. Please set TMDB_API_KEY in your .env file.');
        }
    }

    /**
     * Search for movies by title
     *
     * @param string $query
     * @param int $page
     * @return array
     */
    public function searchMovies(string $query, int $page = 1): array
    {
        $cacheKey = "tmdb.search.{$query}.{$page}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($query, $page) {
            $response = Http::get("{$this->baseUrl}/search/movie", [
                'api_key' => $this->apiKey,
                'query' => $query,
                'page' => $page,
            ]);
            Log::info("TMDB search response", [
                'query' => $query,
                'page' => $page,
                'response' => $response->json(),
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'page' => $page,
                'results' => [],
                'total_pages' => 0,
                'total_results' => 0,
            ];
        });
    }

    /**
     * Get movie details by TMDB ID
     *
     * @param int $tmdbId
     * @return array|null
     */
    public function getMovieDetails(int $tmdbId): ?array
    {
        $cacheKey = "tmdb.movie.{$tmdbId}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($tmdbId) {
            $response = Http::get("{$this->baseUrl}/movie/{$tmdbId}", [
                'api_key' => $this->apiKey,
                'append_to_response' => 'credits,videos,images',
            ]);

            return $response->successful() ? $response->json() : null;
        });
    }

    /**
     * Get popular movies
     *
     * @param int $page
     * @return array
     */
    public function getPopularMovies(int $page = 1): array
    {
        $cacheKey = "tmdb.popular.{$page}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($page) {
            $response = Http::get("{$this->baseUrl}/movie/popular", [
                'api_key' => $this->apiKey,
                'page' => $page,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'page' => $page,
                'results' => [],
                'total_pages' => 0,
                'total_results' => 0,
            ];
        });
    }

    /**
     * Get movie genres
     *
     * @return array
     */
    public function getGenres(): array
    {
        $cacheKey = 'tmdb.genres';

        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            $response = Http::get("{$this->baseUrl}/genre/movie/list", [
                'api_key' => $this->apiKey,
            ]);

            return $response->successful() ? $response->json('genres', []) : [];
        });
    }

    /**
     * Fetch genres from TMDB and store them in the database
     */
    public function fetchAndStoreGenres(): array
    {
        $genres = $this->getGenres();
        if (!empty($genres)) {
            foreach ($genres as $genre) {
                Genre::updateOrCreate(
                    ['tmdb_id' => $genre['id']],
                    ['name' => $genre['name']]
                );
            }
        }
        return $genres;
    }

    /**
     * Get genre name by TMDB ID from the local DB
     */
    public function getGenreNameById($id)
    {
        $genre = Genre::where('tmdb_id', $id)->first();
        return $genre ? $genre->name : null;
    }
}
