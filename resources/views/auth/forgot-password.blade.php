@extends('layouts.guest')

@section('title', 'Forgot Password - Training & Placement System')

@section('content')
<div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Forgot Password?</h2>
            <p class="text-gray-600 text-sm">No worries! Enter your email and we'll send you reset instructions.</p>
        </div>

        @if(session('status'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                <p class="text-sm text-green-800">{{ session('status') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                <p class="text-sm text-red-800">{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="label">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    class="input-field @error('email') border-red-500 @enderror"
                    required
                    autofocus
                >
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full btn-primary py-3 font-semibold">
                Send Reset Link
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center justify-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Login
            </a>
        </div>
    </div>
</div>
@endsection
