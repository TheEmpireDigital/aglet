<?php

namespace App\Http\Controllers;

use App\Http\Requests\FavoriteMovieRequest;
use App\Models\Movie;
use App\Models\UserFavorite;
use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    protected TmdbService $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    /**
     * Display the favorites page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favorites = auth()->user()->favoriteMovies()->with('movie')->get();
        return view('favorites.index', compact('favorites'));
    }

    /**
     * Add a movie to user's favorites.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $movie
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $movie)
    {
        $user = auth()->user();
        $movieRecord = Movie::where('tmdb_id', $movie)->first();
        if (!$movieRecord) {
            $tmdbMovie = $this->tmdbService->getMovieDetails($movie);
            if (!$tmdbMovie) {
                return response()->json(['message' => 'Movie not found'], 404);
            }
            $movieRecord = Movie::create([
                'tmdb_id' => $movie,
                'title' => $tmdbMovie['title'],
                'overview' => $tmdbMovie['overview'],
                'poster_path' => $tmdbMovie['poster_path'],
                'release_date' => $tmdbMovie['release_date'],
                'vote_average' => $tmdbMovie['vote_average'],
                'vote_count' => $tmdbMovie['vote_count'],
                'genres' => $tmdbMovie['genres'] ?? [],
            ]);
        }
        $user->favoriteMovies()->attach($movieRecord->tmdb_id);
        return response()->json(['message' => 'Movie added to favorites', 'movie' => $movieRecord], 201);
    }

    /**
     * Remove a movie from user's favorites.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $movie)
    {
        $user = auth()->user();
        $user->favoriteMovies()->detach($movie);
        return response()->json(['message' => 'Movie removed from favorites']);
    }
}