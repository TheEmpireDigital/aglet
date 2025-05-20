@props(['movie'])

@php
    $posterPath = $movie->poster_path
        ? 'https://image.tmdb.org/t/p/w500' . $movie->poster_path
        : 'https://via.placeholder.com/500x750?text=No+Poster';

    use Carbon\Carbon;
    $releaseDate = $movie->release_date
        ? Carbon::parse($movie->release_date)->format('F d, Y')
        : 'N/A';

    $voteAverage = $movie->vote_average ? number_format($movie->vote_average, 1) : 'N/A';
    $isFavorite = $movie->is_favorite;
    $favoriteBtnClass = $isFavorite
        ? 'bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600'
        : 'bg-gradient-to-r from-indigo-500 to-pink-500 hover:from-indigo-600 hover:to-pink-600';
    $favoriteBtnText = $isFavorite ? 'Saved' : 'Save';
    $favoriteBtnIcon = $isFavorite ? 'fill-current text-white animate-pulse' : 'fill-none text-white';
@endphp

<div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl hover:-translate-y-1 hover:scale-105 transition-all duration-300 min-h-[40rem] h-full border border-gray-100">
    <a href="{{ route('movies.show', $movie->tmdb_id) }}">
        <img src="{{ $posterPath }}" alt="{{ $movie->title }}" class="w-full h-[40rem] object-cover">
    </a>
    <div class="p-5">
        <div class="flex justify-between items-start mb-2">
            <a href="{{ route('movies.show', $movie->tmdb_id) }}" class="hover:underline">
                <h3 class="text-xl font-bold text-gray-900 mb-1 truncate" title="{{ $movie->title }}">
                    {{ $movie->title }}
                </h3>
            </a>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-indigo-400 to-pink-400 text-white shadow-md">
                <svg class="w-4 h-4 mr-1 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                {{ $voteAverage }}
            </span>
        </div>
        <p class="text-sm text-gray-500 mb-3">{{ $releaseDate }}</p>
        <div class="flex justify-between items-center">
            <a href="{{ route('movies.show', $movie->tmdb_id) }}" class="text-indigo-600 hover:text-pink-500 text-sm font-semibold transition-colors duration-200 underline underline-offset-4">
                View Details
            </a>
            <button class="favorite-btn inline-flex items-center px-4 py-2 border-0 text-xs font-semibold rounded-xl shadow-md text-white {{ $favoriteBtnClass }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 transition-all duration-200 group"
                    data-movie-id="{{ $movie->tmdb_id }}" data-is-favorite="{{ $isFavorite ? '1' : '0' }}">
                <svg class="h-5 w-5 mr-1 {{ $favoriteBtnIcon }} group-hover:scale-110 transition-transform duration-200" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                {{ $favoriteBtnText }}
            </button>
        </div>
    </div>
</div>
