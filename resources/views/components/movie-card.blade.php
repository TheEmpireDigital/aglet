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
        ? 'bg-green-600 hover:bg-green-700'
        : 'bg-indigo-600 hover:bg-indigo-700';
    $favoriteBtnText = $isFavorite ? 'Saved' : 'Save';
    $favoriteBtnIcon = $isFavorite ? 'fill-current' : 'fill-none';
@endphp

<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 min-h-[40rem] h-full">
    <a href="{{ route('movies.show', $movie->tmdb_id) }}">
        <img src="{{ $posterPath }}" alt="{{ $movie->title }}" class="w-full h-[40rem] object-cover">
    </a>
    <div class="p-4">
        <div class="flex justify-between items-start">
            <a href="{{ route('movies.show', $movie->tmdb_id) }}" class="hover:underline">
                <h3 class="text-lg font-semibold text-gray-900 mb-1 truncate" title="{{ $movie->title }}">
                    {{ $movie->title }}
                </h3>
            </a>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                {{ $voteAverage }}
            </span>
        </div>
        <p class="text-sm text-gray-500 mb-3">{{ $releaseDate }}</p>
        <div class="flex justify-between items-center">
            <a href="{{ route('movies.show', $movie->tmdb_id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                View Details
            </a>
            <button class="favorite-btn inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white {{ $favoriteBtnClass }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    data-movie-id="{{ $movie->tmdb_id }}" data-is-favorite="{{ $isFavorite ? '1' : '0' }}">
                <svg class="h-4 w-4 mr-1 {{ $favoriteBtnIcon }}" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                {{ $favoriteBtnText }}
            </button>
        </div>
    </div>
</div>
