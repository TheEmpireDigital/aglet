@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-12">
    <h1 class="text-3xl font-bold mb-6 text-center">Contact Information</h1>
    <div class="bg-white shadow-lg rounded-xl px-8 pt-8 pb-8 mb-4 text-center">
        <div class="mb-6 flex flex-col items-center gap-2">
            <span class="inline-flex items-center gap-2 text-lg font-semibold">
                <i class="fas fa-user text-indigo-600"></i> Mugove Machaka
            </span>
            <span class="inline-flex items-center gap-2 text-base">
                <i class="fas fa-building text-indigo-600"></i> The Empire Digital
            </span>
            <span class="inline-flex items-center gap-2 text-base">
                <i class="fas fa-phone text-indigo-600"></i> <a href="tel:+27745428879" class="text-indigo-600 hover:underline">+27 74 542 8879</a>
            </span>
            <span class="inline-flex items-center gap-2 text-base">
                <i class="fas fa-envelope text-indigo-600"></i> <a href="mailto:mugovemachaka@gmail.com" class="text-indigo-600 hover:underline">mugovemachaka@gmail.com</a>
            </span>
        </div>
        <div class="flex flex-wrap justify-center gap-4 mt-6">
            <a href="https://www.linkedin.com/in/mugovemachaka" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-700 text-white hover:bg-blue-800 transition" target="_blank">
                <i class="fab fa-linkedin"></i> LinkedIn
            </a>
            <a href="https://www.facebook.com/TheEmpireDigital" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition" target="_blank">
                <i class="fab fa-facebook"></i> Facebook
            </a>
            <a href="https://vakara.africa/" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-800 text-white hover:bg-gray-900 transition" target="_blank">
                <i class="fas fa-globe"></i> Website
            </a>
            <a href="https://github.com/TheEmpireDigital" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-gray-800 transition" target="_blank">
                <i class="fab fa-github"></i> Github
            </a>
        </div>
    </div>
</div>
<!-- Font Awesome CDN for icons -->
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>
@endpush
@endsection 