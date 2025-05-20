@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 via-white to-pink-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white/90 shadow-xl rounded-2xl p-8 border border-gray-100">
        <div class="flex flex-col items-center">
            <svg class="w-12 h-12 text-indigo-500 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            <h2 class="text-3xl font-extrabold text-gray-900 text-center">Reset your password</h2>
            @if ($errors->any())
                <div class="mt-4 w-full">
                    <ul class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-2 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <form class="mt-8 space-y-6" action="{{ route('password.update') }}" method="POST" autocomplete="off">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="space-y-6">
                <div class="relative">
                    <input id="email" name="email" type="email" required autofocus class="peer block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 pt-6 pb-2 text-gray-900 placeholder-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:bg-white transition-all duration-200" placeholder="Email address" />
                    <label for="email" class="absolute left-4 top-2 text-gray-500 text-sm transition-all duration-200 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-indigo-600">Email address</label>
                </div>
                <div class="relative">
                    <input id="password" name="password" type="password" required class="peer block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 pt-6 pb-2 text-gray-900 placeholder-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:bg-white transition-all duration-200" placeholder="Password" />
                    <label for="password" class="absolute left-4 top-2 text-gray-500 text-sm transition-all duration-200 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-indigo-600">Password</label>
                </div>
                <div class="relative">
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="peer block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 pt-6 pb-2 text-gray-900 placeholder-transparent focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:bg-white transition-all duration-200" placeholder="Confirm Password" />
                    <label for="password_confirmation" class="absolute left-4 top-2 text-gray-500 text-sm transition-all duration-200 peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-indigo-600">Confirm Password</label>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent text-base font-semibold rounded-lg shadow-sm text-white bg-gradient-to-r from-indigo-500 to-pink-500 hover:from-indigo-600 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 transition-all duration-200">
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        Reset Password
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
