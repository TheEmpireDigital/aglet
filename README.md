# MovieDB - Laravel Movie Web Application

## Project Overview

MovieDB is a modern web application for browsing, searching, and saving favorite movies. It features a clean UI/UX and user authentication, built with Laravel and modern frontend tooling (vite + blade + tailwind css).

> **Note:** The search functionality could not be made to work as intended.

---

## 🚀 Setup Instructions

### Prerequisites
- PHP >= 8.1
- Composer
- Node.js & npm
- A database (e.g., MySQL, MariaDb, SQLite)

### Installation
1. **Clone the repository:**
   ```bash
   git clone <your-repo-url>
   cd aglet
   ```
2. **Install PHP dependencies:**
   ```bash
   composer install
   ```
3. **Install JS dependencies:**
   ```bash
   npm install
   ```
4. **Copy and configure environment:**
   ```bash
   cp .env.example .env
   # Edit .env to set DB credentials and TMDB API key
   php artisan key:generate
   ```
5. **Run migrations and seeders:**
   ```bash
   php artisan migrate --seed
   ```
6. **Build frontend assets:**
   ```bash
   npm run build
   # For development: npm run dev
   ```
7. **Start the development server:**
   ```bash
   php artisan serve
   ```

---

## 💡 Rationale & Approach

### Why Laravel & PHP?
- **Laravel** is a mature, expressive PHP framework with a strong ecosystem, ideal for rapid development and maintainability.
- **PHP** is widely supported, easy to deploy, and integrates well with modern frontend JS libraries and frameworks as well as (Vite, Tailwind, etc.).

### Design & Architecture
- **SOLID Principles:**
  - Controllers are thin, delegating logic to services and models.
  - Code is modular.
- **UI/UX:**
  - Clean, responsive design using Tailwind CSS.
  - Consistent navigation and footer, with a sticky footer for blank pages.
  - Modern, accessible auth & reg forms
  - User feedback using custom toaster.
- **Features:**
  - **Search:** AJAX-powered, paginated, and user-friendly. (Not complete)
  - **Favorites:** Instant add/remove with AJAX, persistent per user.
  - **Genres:** Synced from TMDB, displayed as names.
  - **Authentication:** Laravel Sanctum for handling secure login/register.
- **Code Quality:**
  - Follows Laravel and PHP best practices.
  - Error handling and form / request validation throughout.

### Technology Choices
- **Laravel:** Backend framework for routing, ORM, and authentication.
- **Tailwind CSS:** Utility-first CSS for rapid, consistent UI.
- **Vite:** Modern asset bundler for fast builds and HMR.
- **TMDB API:** Source for movie and genre data.
- **Font Awesome:** Iconography for UI polish.
- **Caching:** API request caching


