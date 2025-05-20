<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Services\TmdbService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class MovieController extends Controller
{
    protected TmdbService $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    /**
     * Display the movie listing page.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $perPage = 9;
        $maxPages = 5;
        $maxMovies = $perPage * $maxPages;
        $allMovies = collect();

        // Fetch up to 45 movies (5 pages, 9 per page)
        for ($apiPage = 1; $apiPage <= $maxPages; $apiPage++) {
            $popularMovies = $this->tmdbService->getPopularMovies($apiPage);
            $allMovies = $allMovies->concat($popularMovies['results']);
            if ($allMovies->count() >= $maxMovies) {
                $allMovies = $allMovies->slice(0, $maxMovies);
                break;
            }
        }

        $page = $request->input('page', 1);
        $movies = $allMovies->slice(($page - 1) * $perPage, $perPage)->map(function ($movie) {
            $movieRecord = Movie::where('tmdb_id', $movie['id'])->first();
            if (!$movieRecord) {
                $movieRecord = Movie::create([
                    'tmdb_id' => $movie['id'],
                    'title' => $movie['title'],
                    'overview' => $movie['overview'],
                    'poster_path' => $movie['poster_path'],
                    'release_date' => $movie['release_date'],
                    'vote_average' => $movie['vote_average'],
                    'vote_count' => $movie['vote_count'] ?? 0,
                    'genres' => $movie['genre_ids'] ?? [],
                ]);
            }
            $movieRecord->is_favorite = auth()->check() && auth()->user()->favoriteMovies()->where('movie_tmdb_id', $movie['id'])->exists();
            return $movieRecord;
        })->values();

        $paginator = new LengthAwarePaginator(
            $movies,
            $allMovies->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('movies.index', [
            'movies' => $movies,
            'paginator' => $paginator,
        ]);
    }

    /**
     * Display a specific movie.
     *
     * @param  string  $movie
     * @return View
     */
    public function show(string $movie): View
    {
        // First, check if the movie exists in our database
        $movieRecord = Movie::where('tmdb_id', $movie)->first();

        if (!$movieRecord) {
            // If not found, fetch from TMDB and create locally
            $movieDetails = $this->tmdbService->getMovieDetails($movie);
            if (!$movieDetails) {
                abort(404, 'Movie not found');
            }
            $movieRecord = Movie::create([
                'tmdb_id' => $movie,
                'title' => $movieDetails->title,
                'overview' => $movieDetails->overview,
                'poster_path' => $movieDetails->poster_path,
                'release_date' => $movieDetails->release_date,
                'vote_average' => $movieDetails->vote_average,
                'vote_count' => $movieDetails->vote_count ?? 0,
                'genres' => $movieDetails->genres ?? [],
            ]);
        }

        // Add favorite status
        $movieRecord->is_favorite = auth()->check() && auth()->user()->favoriteMovies()->where('movie_tmdb_id', $movie)->exists();

        // Map genre IDs to names
        $genreNames = [];
        if (is_array($movieRecord->genres)) {
            foreach ($movieRecord->genres as $genre) {
                if (is_array($genre) && isset($genre['id'])) {
                    $name = $this->tmdbService->getGenreNameById($genre['id']);
                } else {
                    $name = $this->tmdbService->getGenreNameById($genre);
                }
                if ($name) {
                    $genreNames[] = $name;
                }
            }
        }

        return view('movies.show', compact('movieRecord', 'genreNames'));
    }

    /**
     * Display the favorites page.
     *
     * @return View
     */
    public function favorites(): View
    {
        $favorites = auth()->user()->favoriteMovies()->get();
        foreach ($favorites as $movie) {
            $movie->is_favorite = true;
        }
        return view('favorites.index', compact('favorites'));
    }

    /**
     * Search movies (AJAX endpoint for JS search)
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $page = $request->input('page', 1);
        if (!$query) {
            return response()->json([
                'results' => [],
                'page' => 1,
                'total_pages' => 0,
                'total_results' => 0,
            ]);
        }
        $results = $this->tmdbService->searchMovies($query, $page);
        Log::info("Search results", ["results" => $results]);
        return response()->json($results);
    }
}
