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
                <div class="flex">
                    <input type="text" id="searchInput" name="query"
                           class="flex-1 min-w-0 block w-full px-3 py-3 rounded-l-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 bg-white/90 placeholder-gray-700 text-gray-900"
                           placeholder="Search for a movie..." required>
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-r-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
