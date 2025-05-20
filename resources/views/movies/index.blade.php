@extends('layouts.app')

@section('content')
    <!-- Hero Section with Background Image -->
    @php
        $moviesWithPoster = collect($movies)->filter(function($movie) {
            return !empty($movie->poster_path);
        })->values();
        $heroPoster = $moviesWithPoster->isNotEmpty()
            ? 'https://image.tmdb.org/t/p/original' . $moviesWithPoster->random()->poster_path
            : 'https://via.placeholder.com/1200x400?text=Movie+Banner';
    @endphp
    <div class="relative w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] h-80 flex items-center justify-center">
        <div class="absolute inset-0">
            <img src="{{ $heroPoster }}" alt="Movie Background" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-transparent"></div>
        </div>
        <div class="relative z-10 w-full text-center">
            <h1 class="text-4xl font-bold text-white mb-4 drop-shadow-lg">Discover Movies</h1>
            <p class="text-xl text-gray-200 mb-8 drop-shadow-lg">Search for your favorite movies and add them to your watchlist</p>
            <!-- Search Form -->
            <form id="searchForm" class="max-w-2xl mx-auto mb-0">
                <div class="flex bg-white/80 rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="relative flex-1">
                        <input type="text" id="searchInput" name="query"
                               class="peer block w-full px-4 pt-6 pb-2 bg-transparent text-gray-900 placeholder-transparent focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition-all duration-200" 
                               placeholder="Search for a movie..." required />
                        <label for="searchInput" class="absolute left-4 top-2 text-gray-500 text-sm transition-all duration-200 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-indigo-600">Search for a movie...</label>
                    </div>
                    <button type="submit" class="inline-flex items-center px-6 py-3 border-0 text-base font-semibold rounded-none rounded-r-xl text-white bg-gradient-to-r from-indigo-500 to-pink-500 hover:from-indigo-600 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="hidden text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-600"></div>
        <p class="mt-4 text-gray-600">Searching for movies...</p>
    </div>
    <!-- Popular Movies -->
    <div id="popularMovies" class="mt-16">
        <h2 id="moviesTitle" class="text-2xl font-bold text-gray-900 mb-6">Popular Movies</h2>
        <div id="moviesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
            @foreach($movies as $movie)
                @include('components.movie-card', ['movie' => $movie])
            @endforeach
        </div>
        <!-- Pagination -->
        <div id="serverPagination" class="mt-8 flex flex-col items-center gap-4 text-center">
            <div class="inline-flex space-x-2">
                {{ $paginator->links() }}
            </div>
        </div>
    </div>
@endsection
