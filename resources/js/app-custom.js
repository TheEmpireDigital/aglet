document.addEventListener('DOMContentLoaded', function() {
    // Utility SVG functions
    function svgSpinner() {
        return `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>`;
    }
    function svgHeart(filled = false) {
        return `<svg class="h-4 w-4 mr-1 ${filled ? 'fill-current' : 'fill-none'}" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>`;
    }

    function showNotification(message, type = 'info') {
        const existingNotification = document.getElementById('notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        const notification = document.createElement('div');
        notification.id = 'notification';
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-md shadow-lg text-white ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            'bg-blue-500'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(notification);
        setTimeout(() => {
            notification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 3000);
    }

    function updateFavoriteButton(button, isFavorite) {
        button.innerHTML = svgHeart(isFavorite) + (isFavorite ? 'Saved' : 'Save');
        // Remove all possible gradient classes first
        button.classList.remove(
            'bg-gradient-to-r',
            'from-yellow-400', 'to-orange-500', 'hover:from-yellow-500', 'hover:to-orange-600',
            'from-indigo-500', 'to-pink-500', 'hover:from-indigo-600', 'hover:to-pink-600'
        );
        if (isFavorite) {
            button.classList.add(
                'bg-gradient-to-r',
                'from-yellow-400', 'to-orange-500',
                'hover:from-yellow-500', 'hover:to-orange-600'
            );
        } else {
            button.classList.add(
                'bg-gradient-to-r',
                'from-indigo-500', 'to-pink-500',
                'hover:from-indigo-600', 'hover:to-pink-600'
            );
        }
        button.setAttribute('data-is-favorite', isFavorite ? '1' : '0');
    }

    // Event delegation for favorite buttons
    document.body.addEventListener('click', function(e) {
        const button = e.target.closest('.favorite-btn');
        if (!button) return;

        e.preventDefault();
        const movieId = button.getAttribute('data-movie-id');
        let isFavorite = button.getAttribute('data-is-favorite') === '1';
        const isAuthenticated = document.body.getAttribute('data-authenticated') === '1';

        if (!isAuthenticated) {
            window.location.href = window.loginRoute || '/login';
            return;
        }

        button.disabled = true;
        button.innerHTML = svgSpinner() + 'Saving...';

        const url = `/favorites/${movieId}`;
        const method = isFavorite ? 'DELETE' : 'POST';

        fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: method === 'POST' ? JSON.stringify({}) : undefined
        })
        .then(async response => {
            let data;
            try {
                data = await response.json();
            } catch (e) {
                data = {};
                console.error(e);
            }
            if (!response.ok) {
                showNotification(data.message || 'An error occurred. Please try again.', 'error');
                throw new Error(data.message || 'Request failed');
            }
            return data;
        })
        .then(data => {
            if (method === 'POST' && data.message && data.message.includes('added')) {
                updateFavoriteButton(button, true);
                showNotification(data.message, 'success');
                const status = document.getElementById('favorite-status');
                if (status) status.textContent = 'In favorites';
            } else if (method === 'DELETE' && data.message && data.message.includes('removed')) {
                // Remove card if on favorites page
                let card = button.closest('.bg-white.rounded-lg.shadow-md');
                if (card && window.location.pathname.includes('/favorites')) {
                    card.remove();
                } else {
                    updateFavoriteButton(button, false);
                    const status = document.getElementById('favorite-status');
                    if (status) status.textContent = 'Not in favorites';
                }
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message || 'An error occurred. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            button.disabled = false;
        });
    });
});

// --- Search Movies JS ---
(function() {
    // Get DOM elements
    const searchForm = document.querySelector('#searchForm');
    const searchInput = document.querySelector('#searchInput');
    const moviesContainer = document.querySelector('#popularMovies');
    const loadingIndicator = document.querySelector('#loadingIndicator');
    const moviesGrid = document.querySelector('#moviesGrid');
    const moviesTitle = document.querySelector('#moviesTitle');
    const pagination = document.querySelector('#pagination');

    if (!searchForm || !searchInput || !moviesContainer || !loadingIndicator || !moviesGrid || !moviesTitle || !pagination) {
        // Not on the movies index page
        return;
    }

    let currentPage = 1;
    let totalPages = 1;
    let currentQuery = '';

    // Handle search form submission
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Search form submit intercepted!');
        currentQuery = searchInput.value.trim();
        currentPage = 1;
        const serverPagination = document.getElementById('serverPagination');
        if (currentQuery) {
            if (serverPagination) serverPagination.style.display = 'none';
            searchMovies(currentQuery, currentPage);
        } else {
            // If no query, show popular movies
            moviesContainer.classList.remove('hidden');
            moviesTitle.textContent = 'Popular Movies';
            if (serverPagination) serverPagination.style.display = '';
        }
    });

    // Also show server pagination if search input is cleared
    searchInput.addEventListener('input', function() {
        const serverPagination = document.getElementById('serverPagination');
        if (!this.value.trim() && serverPagination) {
            serverPagination.style.display = '';
        }
    });

    // Search movies function
    function searchMovies(query, page) {
        // Show loading indicator
        loadingIndicator.classList.remove('hidden');
        moviesContainer.classList.add('hidden');

        // Make API request
        fetch(`/movies/search?query=${encodeURIComponent(query)}&page=${page}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                displaySearchResults(data);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while searching for movies. Please try again.');
            })
            .finally(() => {
                loadingIndicator.classList.add('hidden');
            });
    }

    // Display search results
    function displaySearchResults(data) {
        moviesGrid.innerHTML = '';

        if (data.results.length === 0) {
            moviesGrid.innerHTML = '<p class="col-span-full text-center text-gray-500 py-8">No movies found. Try a different search term.</p>';
            moviesTitle.textContent = 'No Results Found';
        } else {
            // Display movies
            data.results.forEach(movie => {
                const movieCard = createMovieCard(movie);
                moviesGrid.appendChild(movieCard);
            });

            // Update pagination
            updatePagination(data.page, data.total_pages);

            // Update title
            moviesTitle.textContent = 'Search Results';
        }

        // Show movies container
        moviesContainer.classList.remove('hidden');
    }

    // Create movie card element
    function createMovieCard(movie) {
        const movieCard = document.createElement('div');
        movieCard.className = 'bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300';

        const posterPath = movie.poster_path
            ? `https://image.tmdb.org/t/p/w500${movie.poster_path}`
            : 'https://via.placeholder.com/500x750?text=No+Poster';

        const releaseYear = movie.release_date ? new Date(movie.release_date).getFullYear() : 'N/A';

        movieCard.innerHTML = `
            <img src="${posterPath}" alt="${movie.title}" class="w-full h-96 object-cover">
            <div class="p-4">
                <div class="flex justify-between items-start">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1 truncate">${movie.title}</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        ${movie.vote_average ? movie.vote_average.toFixed(1) : 'N/A'}
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-3">${releaseYear}</p>
            </div>
        `;
        return movieCard;
    }

    // Update pagination
    function updatePagination(currentPage, totalPages) {
        if (totalPages <= 1) {
            pagination.innerHTML = '';
            return;
        }

        let paginationHtml = '<nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">';

        // Previous button
        const prevDisabled = currentPage === 1 ? 'opacity-50 cursor-not-allowed' : '';
        paginationHtml += `
            <button ${currentPage === 1 ? 'disabled' : ''}
                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ${prevDisabled}"
                    onclick="window.currentPage = ${currentPage - 1}; searchMovies('${currentQuery}', ${currentPage - 1});">
                <span class="sr-only">Previous</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </button>`;

        // Page numbers
        const maxPagesToShow = 5;
        let startPage, endPage;

        if (totalPages <= maxPagesToShow) {
            const maxPagesBeforeCurrent = Math.floor(maxPagesToShow / 2);
            const maxPagesAfterCurrent = Math.ceil(maxPagesToShow / 2) - 1;

            if (currentPage <= maxPagesBeforeCurrent) {
                startPage = 1;
                endPage = maxPagesToShow;
            } else if (currentPage + maxPagesAfterCurrent >= totalPages) {
                startPage = totalPages - maxPagesToShow + 1;
                endPage = totalPages;
            } else {
                startPage = currentPage - maxPagesBeforeCurrent;
                endPage = currentPage + maxPagesAfterCurrent;
            }
        }

        // First page
        if (startPage > 1) {
            paginationHtml += `
                <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
                        onclick="window.currentPage = 1; searchMovies('${currentQuery}', 1);">
                    1
                </button>`;

            if (startPage > 2) {
                paginationHtml += `
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                        ...
                    </span>`;
            }
        }

        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            const activeClass = i === currentPage ? 'bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50';
            paginationHtml += `
                <button class="relative inline-flex items-center px-4 py-2 border text-sm font-medium ${activeClass}"
                        onclick="window.currentPage = ${i}; searchMovies('${currentQuery}', ${i});">
                    ${i}
                </button>`;
        }

        // Last page
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationHtml += `
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                        ...
                    </span>`;
            }

            paginationHtml += `
                <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
                        onclick="window.currentPage = ${totalPages}; searchMovies('${currentQuery}', ${totalPages});">
                    ${totalPages}
                </button>`;
        }

        // Next button
        const nextDisabled = currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : '';
        paginationHtml += `
            <button ${currentPage === totalPages ? 'disabled' : ''}"
                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ${nextDisabled}"
                    onclick="window.currentPage = ${currentPage + 1}; searchMovies('${currentQuery}', ${currentPage + 1});">
                <span class="sr-only">Next</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </button>`;

        paginationHtml += '</nav>';
        pagination.innerHTML = paginationHtml;
    }

    // Make searchMovies available globally for pagination
    window.searchMovies = searchMovies;
    window.currentPage = currentPage;
})();
