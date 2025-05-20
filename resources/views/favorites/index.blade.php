@extends('layouts.app')

@section('content')
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Favorite Movies</h1>
        <p class="text-xl text-gray-600">Your favorite movies</p>
    </div>
    @if(count($favorites) === 0)
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No favorites yet</h3>
            <p class="mt-1 text-sm text-gray-500">Start adding movies to your favorites by clicking the "Save" button on any movie page.</p>
            <div class="mt-6">
                <a href="{{ route('movies.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Browse Movies
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($favorites as $movie)
                @include('components.movie-card', [
                    'movie' => $movie,
                    'is_favorite' => true
                ])
            @endforeach
        </div>
    @endif
@endsection
