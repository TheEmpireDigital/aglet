@extends('layouts.app')

@section('content')
<div class="flex flex-col lg:flex-row gap-8 mt-10">
    <!-- Movie Poster -->
    <div class="w-full lg:w-1/3">
        <img src="{{ $movieRecord->poster_path ? 'https://image.tmdb.org/t/p/w500' . $movieRecord->poster_path : 'https://via.placeholder.com/300x450?text=No+Poster' }}"
             alt="{{ $movieRecord->title }}"
             class="w-full rounded-lg shadow-lg">
    </div>

    <!-- Movie Details -->
    <div class="w-full lg:w-2/3">
        <div class="space-y-6">
            <!-- Title and Rating -->
            <div class="flex justify-between items-center">
                <h1 class="text-4xl font-bold text-gray-900">{{ $movieRecord->title }}</h1>
                <div class="flex items-center">
                    <span class="text-gray-600 mr-2">Rating:</span>
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="ml-2 text-yellow-400">{{ number_format($movieRecord->vote_average, 1) }}</span>
                </div>
            </div>

            <!-- Overview -->
            <div class="text-gray-600">
                {{ $movieRecord->overview }}
            </div>

            <!-- Release Date and Genres -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="ml-2 text-gray-600">{{ $movieRecord->release_date ? $movieRecord->release_date->format('F d, Y') : 'N/A' }}</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($genreNames as $genreName)
                        <span class="px-3 py-1 text-sm text-gray-600 bg-gray-100 rounded-full">
                            {{ $genreName }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Favorite Button -->
            <div class="flex items-center">
                @auth
                    <button class="favorite-btn flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white {{ auth()->user()->favoriteMovies()->where('movie_tmdb_id', $movieRecord->tmdb_id)->exists() ? 'bg-green-600 hover:bg-green-700' : 'bg-indigo-600 hover:bg-indigo-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" data-movie-id="{{ $movieRecord->tmdb_id }}" data-is-favorite="{{ auth()->user()->favoriteMovies()->where('movie_tmdb_id', $movieRecord->tmdb_id)->exists() ? '1' : '0' }}">
                        <svg class="h-4 w-4 mr-2 {{ auth()->user()->favoriteMovies()->where('movie_tmdb_id', $movieRecord->tmdb_id)->exists() ? 'fill-current' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        {{ auth()->user()->favoriteMovies()->where('movie_tmdb_id', $movieRecord->tmdb_id)->exists() ? 'Saved' : 'Save' }}
                    </button>
                    <div class="ml-4 text-gray-600" id="favorite-status">
                        {{ auth()->user()->favoriteMovies()->where('movie_tmdb_id', $movieRecord->tmdb_id)->exists() ? 'In favorites' : 'Not in favorites' }}
                    </div>
                    @else
                    <button class="favorite-btn flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" data-movie-id="{{ $movieRecord->tmdb_id }}" data-is-favorite="0">
                        <svg class="h-4 w-4 mr-2 fill-none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        Save
                    </button>
                    <div class="ml-4 text-gray-600" id="favorite-status">
                        Not in favorites
                    </div>
                @endauth
            </div>

            <!-- Cast and Crew -->
            @if(isset($movieRecord->credits))
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Cast</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($movieRecord->credits['cast'] as $cast)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <img src="{{ $cast['profile_path'] ? 'https://image.tmdb.org/t/p/w185' . $cast['profile_path'] : 'https://via.placeholder.com/185x278?text=No+Image' }}"
                                         alt="{{ $cast['name'] }}"
                                         class="w-24 h-36 object-cover rounded-lg">
                                    <div class="ml-4">
                                        <h3 class="font-semibold text-gray-900">{{ $cast['name'] }}</h3>
                                        <p class="text-gray-600">{{ $cast['character'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
