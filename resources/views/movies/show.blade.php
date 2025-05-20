@extends('layouts.app')

@section('content')
<div class="flex flex-col lg:flex-row gap-10 mt-12 max-w-5xl mx-auto">
    <!-- Movie Poster -->
    <div class="w-full lg:w-1/3 flex justify-center items-start">
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl border border-gray-100 p-4">
            <img src="{{ $movieRecord->poster_path ? 'https://image.tmdb.org/t/p/w500' . $movieRecord->poster_path : 'https://via.placeholder.com/300x450?text=No+Poster' }}"
                 alt="{{ $movieRecord->title }}"
                 class="w-full rounded-xl shadow-lg object-cover">
        </div>
    </div>

    <!-- Movie Details -->
    <div class="w-full lg:w-2/3">
        <div class="space-y-8">
            <!-- Title and Rating -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <h1 class="text-4xl font-extrabold text-gray-900 drop-shadow-lg">{{ $movieRecord->title }}</h1>
                <div class="flex items-center gap-2">
                    <span class="text-gray-600">Rating:</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-indigo-400 to-pink-400 text-white shadow-md">
                        <svg class="w-4 h-4 mr-1 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="ml-1 text-white">{{ number_format($movieRecord->vote_average, 1) }}</span>
                    </span>
                </div>
            </div>

            <!-- Overview -->
            <div class="text-lg text-gray-700 bg-white/70 rounded-xl p-6 shadow-md border border-gray-100">
                {{ $movieRecord->overview }}
            </div>

            <!-- Release Date and Genres -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="ml-2 text-gray-600">{{ $movieRecord->release_date ? $movieRecord->release_date->format('F d, Y') : 'N/A' }}</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($genreNames as $genreName)
                        <span class="px-3 py-1 text-sm text-white bg-gradient-to-r from-indigo-400 to-pink-400 rounded-full shadow">{{ $genreName }}</span>
                    @endforeach
                </div>
            </div>

            <!-- Favorite Button -->
            <div class="flex items-center mt-2">
                @auth
                    @php
                        $isFavorite = auth()->user()->favoriteMovies()->where('movie_tmdb_id', $movieRecord->tmdb_id)->exists();
                        $favoriteBtnClass = $isFavorite
                            ? 'bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600'
                            : 'bg-gradient-to-r from-indigo-500 to-pink-500 hover:from-indigo-600 hover:to-pink-600';
                        $favoriteBtnIcon = $isFavorite ? 'fill-current text-white animate-pulse' : 'fill-none text-white';
                        $favoriteBtnText = $isFavorite ? 'Saved' : 'Save';
                    @endphp
                    <button class="favorite-btn flex items-center px-5 py-2 border-0 text-sm font-semibold rounded-xl shadow-md text-white {{ $favoriteBtnClass }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 transition-all duration-200 group" data-movie-id="{{ $movieRecord->tmdb_id }}" data-is-favorite="{{ $isFavorite ? '1' : '0' }}">
                        <svg class="h-5 w-5 mr-2 {{ $favoriteBtnIcon }} group-hover:scale-110 transition-transform duration-200" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        {{ $favoriteBtnText }}
                    </button>
                    <div class="ml-4 text-gray-600" id="favorite-status">
                        {{ $isFavorite ? 'In favorites' : 'Not in favorites' }}
                    </div>
                @else
                    <button class="favorite-btn flex items-center px-5 py-2 border-0 text-sm font-semibold rounded-xl shadow-md text-white bg-gradient-to-r from-indigo-500 to-pink-500 hover:from-indigo-600 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 transition-all duration-200 group" data-movie-id="{{ $movieRecord->tmdb_id }}" data-is-favorite="0">
                        <svg class="h-5 w-5 mr-2 fill-none text-white group-hover:scale-110 transition-transform duration-200" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="mt-10">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Cast</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($movieRecord->credits['cast'] as $cast)
                            <div class="bg-white/80 backdrop-blur-md rounded-xl p-4 shadow border border-gray-100 flex flex-col items-center">
                                <img src="{{ $cast['profile_path'] ? 'https://image.tmdb.org/t/p/w185' . $cast['profile_path'] : 'https://via.placeholder.com/185x278?text=No+Image' }}"
                                     alt="{{ $cast['name'] }}"
                                     class="w-24 h-36 object-cover rounded-lg shadow mb-2">
                                <h3 class="font-semibold text-gray-900 text-center">{{ $cast['name'] }}</h3>
                                <p class="text-gray-600 text-center text-sm">{{ $cast['character'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
